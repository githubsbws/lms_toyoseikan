<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Learn;
use App\Models\Orgchart;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use function App\Http\Controllers\orgchart;

class AdminDashboardService
{
    const DEPT_LEVEL = '3';
    const SECTION_LEVEL = '4';
    const LINE_LEVEL = '5';
    const POSITION_LEVEL = '6';
    const ACTIVE = 'y';

    /**
     * แคช orgchart ทั้งต้นไว้ในหน่วยความจำ (ต่อ 1 request) เพื่อไล่ tree โดยไม่ยิง query ซ้ำ
     * เก็บเป็น string ทุกคีย์ เพราะ orgchart.parent_id เป็น varchar แต่ id เป็น integer
     */
    private ?array $orgChildrenMap = null;   // parent_id => [child_id, ...]

    private function loadOrgMaps(): void
    {
        if ($this->orgChildrenMap !== null) {
            return;
        }

        $this->orgChildrenMap = Orgchart::where('active', self::ACTIVE)
            ->get(['id', 'parent_id'])
            ->groupBy(fn ($org) => (string) $org->parent_id)
            ->map(fn ($group) => $group->pluck('id')->map(fn ($id) => (string) $id)->all())
            ->all();
    }
    /**
     * Entry point เดียวให้ Controller เรียก
     *
     * $filters รองรับ:
     *   - department_id  -> กรอง course_online.department_org_id ตรง ๆ
     *   - section_id / line_id -> กรอง "ระดับล่างกว่า department" ผ่าน org_course.orgchart_id
     *     (ถ้ามีทั้งคู่ ใช้ line_id เพราะลึกกว่า section_id)
     *   - team_id -> กรอง users.team_id เฉพาะ query ที่ join ไปถึง users อยู่แล้ว
     *   - date_from, date_to -> กรอง "วันที่เกิดกิจกรรม" ผ่าน applyDateFilter()
     *     ไม่มีคีย์นี้ = ไม่กรองวันที่ (ค่าเริ่มต้นตอนเปิดหน้า)
     *
     * @return array แพ็กเก็ตข้อมูลแยกตามกลุ่มการ์ด/แถวใน view (1 key = 1 การ์ดหรือ 1 ตาราง)
     */

    /**
     * แผนกสำหรับดรอปดาวน์ตัวแรก
     *
     * เอาเฉพาะแผนกที่ยัง "เกาะอยู่กับผังจริง" คือ parent_id ชี้ไปที่โน้ดที่มีอยู่และ active
     * เพราะในตารางมีแถวกำพร้าที่ parent_id ชี้ไปที่ id ที่ไม่มีอยู่ในตาราง (parent_id=3)
     * และชื่อดันซ้ำกับแผนกตัวจริงเป๊ะ ๆ เช่น "HR, GA & Safety Department" มีทั้ง id 745
     * (กำพร้า ไม่มีส่วนงาน ไม่มีผู้ใช้ ไม่มีคอร์ส) และ id 752 (ตัวจริง มี 4 ส่วนงาน)
     * ถ้าโชว์ทั้งคู่ ผู้ใช้แยกไม่ออกและมีโอกาสกดตัวกำพร้าแล้วเจอดรอปดาวน์ส่วนงานว่าง
     *
     * เทียบ parent_id เป็น string เพราะคอลัมน์เป็น varchar แต่ id เป็น integer
     * ถ้าปล่อยให้ Postgres เทียบข้ามชนิดกันจะ error
     */
    public function getDepartment()
    {
        $existingIds = Orgchart::where('active', self::ACTIVE)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        return Orgchart::where('level', self::DEPT_LEVEL)
            ->where('active', self::ACTIVE)
            ->whereIn('parent_id', $existingIds)
            ->orderBy('title')
            ->get();
    }

    public function getTeam()
    {
        $team = Team::where('active',self::ACTIVE)->get();
        return $team;
    }

    /**
     * ชนิดของ dropdown ที่ยอมให้ขอได้ (กันไม่ให้ขอระดับ "ตำแหน่ง" ซึ่งแถบ filter
     * ไม่มีช่องรองรับ และ service ก็ยังไม่รองรับ position_id)
     */
    const ORG_TYPE_SECTION = 'section';
    const ORG_TYPE_LINE    = 'line';
    const ORG_TYPES = [self::ORG_TYPE_SECTION, self::ORG_TYPE_LINE];

    /**
     * ดึงลูกของ orgchart node ตรง ๆ (ตัวช่วยพื้นฐาน)
     */
    public function getOrgChildren($parentId, ?string $level = null)
    {
        $query = Orgchart::where('parent_id', $parentId)
            ->where('active', self::ACTIVE);

        if ($level !== null) {
            $query->where('level', $level);
        }

        return $query
            ->orderBy('title')
            ->get(['id', 'title', 'level']);
    }

    /**
     * ส่วนงาน (สายงาน) ของแผนกที่เลือก
     * ใต้แผนกเป็น level 4 เสมอทุกสาย จึงกรองด้วย level ได้ตรง ๆ
     */
    public function getSections($departmentId)
    {
        return $this->getOrgChildren($departmentId, self::SECTION_LEVEL);
    }

    /**
     * ไลน์ผลิตของส่วนงานที่เลือก
     *
     * IMPORTANT: ห้ามตัดสินด้วยเลข level ของตัวโน้ดเอง เพราะคอลัมน์ level เก็บแค่
     * "ความลึก" ไม่ได้เก็บ "บทบาท" และโครงสร้างจริงลึกไม่เท่ากันทุกแผนก:
     *
     *   สายยาว (Production ฯลฯ): แผนก(3) -> ส่วนงาน(4) -> ไลน์ผลิต(5) -> ตำแหน่ง(6)
     *   สายสั้น (HR ฯลฯ)       : แผนก(3) -> ส่วนงาน(4) -> ตำแหน่ง(5)   <-- ไม่มีชั้นไลน์
     *
     * ผลคือ "ตำแหน่ง" ของสายสั้นถูกเก็บเป็น level 5 เลขเดียวกับ "ไลน์ผลิต" ของสายยาว
     * (ข้อมูลจริง: ใต้ส่วนงาน Human Resources เป็น level 5 ชื่อ Manager / Supervisor /
     * Staff ทั้งหมด ส่วนใต้ Production Line 1 เป็น level 5 ชื่อ Mixing Line 1 ฯลฯ)
     *
     * เกณฑ์ที่ใช้: ดูทีละลูกว่า "เป็นชั้นสุดท้ายของสายนั้นหรือยัง"
     * - ลูกที่ไม่มีอะไรต่อจากมันแล้ว = ชั้นสุดท้าย = ตำแหน่ง -> ไม่เอามาใส่ dropdown
     * - ลูกที่ยังมีของต่อข้างใต้      = ยังไม่ใช่ชั้นสุดท้าย = ไลน์ -> เอามาแสดง
     *
     * ถ้าลูกทุกตัวเป็นชั้นสุดท้าย (เช่นสาย HR ที่ใต้ส่วนงานเป็นตำแหน่งเลย) จะได้ []
     * กลับไป ฝั่งหน้าเว็บจะปิดช่องไลน์แล้วขึ้นว่าไม่มีไลน์ผลิต
     */
    public function getProductionLines($sectionId)
    {
        $this->loadOrgMaps();

        return $this->getOrgChildren($sectionId)
            ->filter(fn ($child) => !empty($this->orgChildrenMap[(string) $child->id]))
            ->values();
    }

    /**
     * ดึงตัวเลือก dropdown ตามชนิดที่ขอ (section / line)
     * รวมทางเข้าไว้ที่เดียวเพื่อให้ controller ไม่ต้องรู้เรื่องโครงสร้าง level
     */
    public function getOrgOptions(string $type, $parentId)
    {
        return $type === self::ORG_TYPE_LINE
            ? $this->getProductionLines($parentId)
            : $this->getSections($parentId);
    }

    public function getDashboardData(array $filters = []): array
    {
        return [
            // แถวที่ 1: การ์ดสรุปตัวเลข 6 ใบ
            'overview' => $this->getOverviewStats($filters),

            // แถวที่ 3 การ์ดที่ 1: คอร์สที่ต้องติดตาม
            'overdueCourses' => $this->getOverdueCourses($filters),

            // แถวที่ 4 ตารางที่ 1: การเรียนรู้ตามแผนก
            'departmentLearning' => $this->getDepartmentLearning($filters),

            // แถวที่ 4 การ์ดที่ 2: หลักสูตรที่มีผู้เรียนมากที่สุด
            'popularCourses' => $this->getPopularCourses($filters),

            // แถวที่ 3 การ์ดที่ 3: สถานะระบบ (พื้นที่จัดเก็บ / ผู้ใช้งานออนไลน์ / การใช้งานวันนี้)
            // ไม่ apply $filters เพราะเป็นสถานะเครื่อง/เซิร์ฟเวอร์ ไม่เกี่ยวกับแผนก/ทีมที่เลือก
            'systemStatus' => $this->getSystemStatus(),
        ];
    }

    /**
     * สถานะระบบ: พื้นที่จัดเก็บ / ผู้ใช้งานออนไลน์ / การใช้งานวันนี้
     *
     * พื้นที่จัดเก็บ: ใช้ disk_total_space()/disk_free_space() ของ PHP เอง — ฟังก์ชันนี้
     * เป็น native PHP function ที่เรียก syscall ที่ถูกต้องให้เองตาม OS ที่ตัวเว็บรันอยู่จริง
     * (Windows เรียก GetDiskFreeSpaceEx, Linux/Unix เรียก statvfs) โดยอัตโนมัติ
     * ไม่ต้องเขียนโค้ดแยกเช็ค PHP_OS/PHP_OS_FAMILY แล้วเลือกคำสั่ง shell เอง
     * (การยิง shell เช่น `df -h` หรือ `wmic` เสี่ยงกว่าและช้ากว่าฟังก์ชัน native มาก)
     *
     * ผู้ใช้งานออนไลน์: นับจาก users.online_status = 1 (คอลัมน์นี้ถูกอัปเดตจริงใน
     * LoginController / CheckSingleLogin / UpdateOnlineStatus listener อยู่แล้ว)
     *
     * การใช้งานวันนี้: นับ record ในตาราง log_users ของวันนี้ (connection pgsql_noprefix
     * ตามที่ประกาศไว้ใน Logusers model จึงไม่ต้องกังวลเรื่อง table prefix ที่นี่)
     *
     * Big O: 2 COUNT query (เร็ว, ใช้ index ธรรมชาติของ PK/status) + 1 native disk syscall
     * ไม่มี loop ไม่มี N+1 ปลอดภัยแม้ users/log_users โตขึ้นหลายเท่า เพราะเป็น COUNT ล้วน
     */
    private function getSystemStatus(): array
    {
        $basePath = base_path();

        $totalBytes = @disk_total_space($basePath) ?: 0;
        $freeBytes  = @disk_free_space($basePath) ?: 0;
        $usedBytes  = max($totalBytes - $freeBytes, 0);

        $usedPercent = $totalBytes > 0
            ? round(($usedBytes / $totalBytes) * 100)
            : 0;

        $onlineUsers = DB::table('users')
            ->where('online_status', 1)
            ->count();

        // $todayUsage = DB::connection('pgsql_noprefix')
        //     ->table('log_users')
        //     ->whereDate('create_date', now()->toDateString())
        //     ->count();

        return [
            'disk_used_gb'    => round($usedBytes / 1024 / 1024 / 1024, 1),
            'disk_total_gb'   => round($totalBytes / 1024 / 1024 / 1024, 1),
            'disk_used_percent' => $usedPercent,
            'online_users'    => $onlineUsers,
            // 'today_usage'     => $todayUsage,
        ];
    }

    /**
     * Filter helper กลาง: apply เงื่อนไข org (department/section/line) ให้ query
     * ที่มีคอลัมน์ course_id / department_org_id (ใช้ได้ทั้ง Eloquent builder ของ Course
     * และ DB::table('course_online')->... เพราะ interface where()/whereIn() เหมือนกัน)
     *
     * Logic:
     * - ไม่เลือกอะไร -> ไม่กรอง (เอาคอร์สทั้งหมด)
     * - เลือก section_id หรือ line_id (ระดับลึกกว่า department) -> ไปดูที่ org_course.orgchart_id
     *   แทน department_org_id (ใช้ line_id ก่อนถ้ามีทั้งคู่ เพราะลึกกว่า)
     * - เลือกแค่ department_id -> กรอง department_org_id ตรง ๆ
     *
     * IMPORTANT: ตรวจข้อมูลจริงในฐานข้อมูลแล้ว org_course.orgchart_id เก็บ "ระดับตำแหน่ง"
     * (level 6) เท่านั้น ไม่ได้เก็บ id ของ section (level 4) หรือ line (level 5) ไว้เลย
     * ดังนั้นถ้า where('orgchart_id', $sectionId) ตรง ๆ จะไม่เจอแถวใดเลย
     * ต้องกาง section/line ที่เลือกออกเป็น "ตำแหน่งลูกหลานทั้งหมด" ก่อนแล้วค่อย whereIn
     *
     * $columnPrefix: ใส่เมื่อ query มี join หลายตารางแล้วต้องระบุตารางให้ชัด
     * (เช่น 'course_online.') ถ้า query อยู่บนตารางคอร์สตรง ๆ ปล่อยว่างไว้
     */
    private function applyOrgFilter($query, array $filters, string $columnPrefix = '')
    {
        $deepOrgId = $filters['line_id'] ?? $filters['section_id'] ?? null;

        if (!empty($deepOrgId)) {
            $orgIds = $this->getDescendantOrgIds($deepOrgId);

            $courseIds = DB::table('org_course')
                ->whereIn('orgchart_id', $orgIds)
                ->distinct()
                ->pluck('course_id');

            $query->whereIn($columnPrefix . 'course_id', $courseIds);
        } elseif (!empty($filters['department_id'])) {
            $query->where($columnPrefix . 'department_org_id', $filters['department_id']);
        }

        return $query;
    }

    /**
     * Filter helper กลาง: apply team_id ให้ query ที่มีคอลัมน์ team_id ของ users
     * (เฉพาะ query ที่ join ไปถึง users แล้วเท่านั้น ถ้าไม่มี users เลยไม่ต้องเรียก)
     */
    private function applyTeamFilter($query, array $filters, string $column = 'team_id')
    {
        if (!empty($filters['team_id'])) {
            $query->where($column, $filters['team_id']);
        }

        return $query;
    }

    /**
     * Filter helper กลาง: apply ช่วงเวลาให้คอลัมน์วันที่ที่ระบุ
     *
     * ไม่ส่ง date_from/date_to มา (ค่าเริ่มต้นตอนเปิดหน้า) = ไม่กรองวันที่เลย
     * รองรับกรณีส่งมาข้างเดียวด้วย (เลือกแต่วันเริ่ม หรือแต่วันจบ)
     *
     * ใช้ whereDate เพื่อเทียบแค่ส่วนวันที่ ไม่เอาเวลา เพราะคอลัมน์เหล่านี้เป็น timestamp
     * ถ้าเทียบตรง ๆ กับ 'Y-m-d' ข้อมูลของวันสุดท้ายที่มีเวลาติดมาจะหลุดออกจากช่วง
     *
     * $column ต้องเป็นชื่อคอลัมน์จากโค้ดเราเองเท่านั้น ห้ามรับจาก request
     */
    private function applyDateFilter($query, array $filters, string $column)
    {
        if (!empty($filters['date_from'])) {
            $query->whereDate($column, '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate($column, '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * แถวที่ 1 (6 การ์ด): คอร์สพนักงานทั่วไป / คอร์สพนักงานใหม่ / วิดีโอ / ไฟล์ / ผู้ใช้ทั้งหมด / รออนุมัติ
     *
     * ตอนนี้ apply filter แล้ว เพื่อให้การ์ดสอดคล้องกับข้อมูลด้านล่าง:
     * 1. total_courses_general — คอร์สทั่วไป (is_onboarding = false) กรอง org ผ่าน applyOrgFilterToQuery()
     * 2. total_courses_new_employee — คอร์สพนักงานใหม่ (is_onboarding = true) กรอง org เหมือนกัน
     *    แยก query กันคนละตัว (ไม่ใช่ query เดียวแล้วนับ 2 เงื่อนไข) เพราะ is_onboarding
     *    เป็นคนละกลุ่มความหมายกันโดยสิ้นเชิง ไม่ใช่แค่ filter ย่อยของ "คอร์สทั้งหมด"
     * 3. total_videos — นับจากตาราง file (join file->lesson->course) กรอง org
     * 4. total_documents — นับจากตาราง file_doc (join file_doc->lesson->course) กรอง org
     *    แยก query กันคนละตัว เพราะวิดีโอกับไฟล์เอกสารเก็บอยู่คนละตาราง ไม่มี type column
     *    ร่วมกันให้ filter ใน query เดียวได้
     * 5. total_users — กรอง "คน" ตาม org hierarchy ผ่าน applyOrgFilterToUsers()
     * 6. pending_approvals — join log_approve->users แล้วกรอง org
     *
     * Trade-off: ถ้า filter หนัก (เช่น เลือก line เล็ก ๆ) ตัวเลขการ์ดจะเล็กลง
     * แต่ถูกต้องตามหลักการ (แสดงเฉพาะข้อมูลที่เลือก)
     */
    private function getOverviewStats(array $filters): array
    {
        // 1. total_courses_general (คอร์สพนักงานทั่วไป)
        $generalCoursesQuery = DB::table('course_online')
            ->where('active', 'y')
            ->where('is_onboarding', false);
        $generalCoursesQuery = $this->applyOrgFilterToQuery($generalCoursesQuery, $filters);
        $totalCoursesGeneral = $generalCoursesQuery->count();

        // 2. total_courses_new_employee (คอร์สพนักงานใหม่)
        $newEmployeeCoursesQuery = DB::table('course_online')
            ->where('active', 'y')
            ->where('is_onboarding', true);
        $newEmployeeCoursesQuery = $this->applyOrgFilterToQuery($newEmployeeCoursesQuery, $filters);
        $totalCoursesNewEmployee = $newEmployeeCoursesQuery->count();

        // 3a. total_videos (join file->lesson->course) — วิดีโอนับที่ตาราง file
        $videosQuery = DB::table('file')
            ->join('lesson', 'file.lesson_id', '=', 'lesson.id')
            ->join('course_online', 'lesson.course_id', '=', 'course_online.course_id')
            ->where('file.active', 'y')
            ->where('course_online.active', 'y');
        $videosQuery = $this->applyOrgFilterToQuery($videosQuery, $filters);
        $totalVideos = $videosQuery->count('file.id');

        // 3b. total_documents (join file_doc->lesson->course) — ไฟล์นับที่ตาราง file_doc
        $documentsQuery = DB::table('file_doc')
            ->join('lesson', 'file_doc.lesson_id', '=', 'lesson.id')
            ->join('course_online', 'lesson.course_id', '=', 'course_online.course_id')
            ->where('file_doc.active', 'y')
            ->where('course_online.active', 'y');
        $documentsQuery = $this->applyOrgFilterToQuery($documentsQuery, $filters);
        $totalDocuments = $documentsQuery->count('file_doc.id');

        // 4. total_users (กรอง org_id ของคน ไม่ใช่คอร์ส)
        $usersQuery = DB::table('users')
            ->where('status', '1');
        $usersQuery = $this->applyOrgFilterToUsers($usersQuery, $filters);
        $totalUsers = $usersQuery->count();

        // 5. pending_approvals (ข้อสอบที่รอตรวจ) — ต้อง join users จริง ๆ ก่อน
        // เพราะ applyOrgFilterToUsers() จะ where('users.org_id', ...) / where('users.team_id', ...)
        // ถ้าไม่ join ให้ก่อน Postgres จะหา FROM-clause ของ tbl_users ไม่เจอ (บั๊กที่แก้อยู่นี้)
        $approvalsQuery = DB::table('coursescore')
            ->join('users', 'coursescore.user_id', '=', 'users.id')
            ->where('coursescore.score_status', 'wait');
        $approvalsQuery = $this->applyOrgFilterToUsers($approvalsQuery, $filters, 'users');
        // ช่วงเวลา: นับข้อสอบที่ถูกส่งมาในช่วงที่เลือก
        $this->applyDateFilter($approvalsQuery, $filters, 'coursescore.create_date');
        $pendingApprovals = $approvalsQuery->count('coursescore.score_id');

        return [
            'total_courses_general'      => $totalCoursesGeneral,
            'total_courses_new_employee' => $totalCoursesNewEmployee,
            'total_videos'                => $totalVideos,
            'total_documents'             => $totalDocuments,
            'total_users'                => $totalUsers,
            'pending_approvals'          => $pendingApprovals,
        ];
    }

    /**
     * Apply org filter ให้ query ที่ join course_online เข้ามา (ต้องระบุชื่อตารางให้ชัด)
     * เป็นแค่ทางผ่านไป applyOrgFilter() เพื่อไม่ให้เงื่อนไข org แตกเป็น 2 ชุดที่ต้องแก้ซ้ำ
     */
    private function applyOrgFilterToQuery($query, array $filters)
    {
        return $this->applyOrgFilter($query, $filters, 'course_online.');
    }

    /**
     * Apply org filter ให้ query ที่มี users (กรอง "คน" ตาม org hierarchy)
     *
     * users.org_id เก็บตำแหน่ง (level ลึกสุด) ถ้าเลือก department/section/line
     * ต้องหา descendant org_id ทั้งหมด (รวมลูกหลานทุกชั้น) แล้ว whereIn
     *
     * $tableAlias: ถ้า join users ใช้ alias (เช่น 'users') ถ้าไม่ join ใช้ ''
     */
    private function applyOrgFilterToUsers($query, array $filters, string $tableAlias = '')
    {
        $prefix = $tableAlias ? $tableAlias . '.' : '';

        $selectedOrgId = $filters['line_id'] ?? $filters['section_id'] ?? $filters['department_id'] ?? null;

        if (!empty($selectedOrgId)) {
            $descendantIds = $this->getDescendantOrgIds($selectedOrgId);
            $query->whereIn($prefix . 'org_id', $descendantIds);
        }

        if (!empty($filters['team_id'])) {
            $query->where($prefix . 'team_id', $filters['team_id']);
        }

        return $query;
    }

    /**
     * หา org id ทั้งหมดใน subtree ของ $rootId (รวมตัวมันเองด้วย)
     *
     * ใช้ 2 ที่:
     * - กาง section/line ที่เลือก -> ตำแหน่งลูกหลาน เพื่อไปหา course ใน org_course
     * - กรอง users.org_id ที่เก็บ "ตำแหน่ง" ซึ่งลึกกว่า department/section/line ที่เลือก
     *
     * ดึง orgchart ทั้งต้นครั้งเดียวแล้วไล่ tree ใน PHP (ไม่ยิง query ต่อ 1 โน้ด)
     * และเทียบเป็น string ตลอด เพราะ orgchart.parent_id เป็น varchar แต่ orgchart.id
     * เป็น integer — ถ้าเทียบข้ามชนิดกับ Postgres จะพังได้
     */
    private function getDescendantOrgIds($rootId): array
    {
        $this->loadOrgMaps();

        $rootId = (string) $rootId;
        $ids    = [$rootId];
        $queue  = [$rootId];

        while ($queue) {
            $current = array_pop($queue);

            foreach ($this->orgChildrenMap[$current] ?? [] as $childId) {
                $ids[]   = $childId;
                $queue[] = $childId;
            }
        }

        return $ids;
    }

    /**
     * คอร์สที่ต้องติดตาม (Overdue): คอร์สที่ end_date ผ่านมาแล้ว
     * และยังมีผู้เรียนที่ยังไม่ผ่าน (passcourse ยัง pass ไม่ครบ)
     * เรียงจาก end_date ที่ใกล้วันปัจจุบันมากที่สุดก่อน (overdue ล่าสุด) เอามา 5 อันดับแรก
     *
     * รับค่า filter จากแถบค้นหาหน้า admindashboard ทั้งหมด:
     * - department_id (แผนก)  -> กรองที่ course_online.department_org_id ตรง ๆ
     * - section_id (ส่วนงาน) / line_id (สายงาน/ไลน์ผลิต) -> กางเป็นตำแหน่งลูกหลาน
     *   แล้วไปหา course ที่ผูกไว้ใน org_course (ใช้ line_id ก่อนถ้าเลือกมาทั้งคู่ เพราะลึกกว่า)
     *   ทั้งสองเคสจัดการอยู่ใน applyOrgFilter() ที่เดียว
     * - team_id (ทีม) -> ใช้กรอง "ผู้เรียนที่ยังไม่ผ่าน" เท่านั้น (ทั้งเงื่อนไขว่าคอร์สนี้
     *   ยังมีคนค้างอยู่ไหม และตัวเลข unfinished ที่เอาไปแสดง) เพราะทีมผูกกับ users
     *   ไม่ได้ผูกกับตัวคอร์ส ถ้าเลือกทีมมาแล้วคอร์สนั้นไม่มีคนในทีมค้างเลย ก็ไม่ควรโชว์
     */
    private function getOverdueCourses(array $filters, int $limit = 5)
    {
        // NOTE: ใช้ whereHas (สร้าง EXISTS subquery) แทน having() บน alias จาก withCount
        // เพราะ PostgreSQL ไม่ยอมให้ HAVING อ้างชื่อ alias ที่มาจาก SELECT ได้ (ต่างจาก MySQL)
        // withCount ยังคงไว้เพื่อดึงตัวเลข unfinished ไปแสดงผล
        $unfinishedCondition = function ($q) use ($filters) {
            $q->where('passcours_status', '!=', 'pass');

            if (!empty($filters['team_id'])) {
                $q->whereHas('user', function ($uq) use ($filters) {
                    $uq->where('team_id', $filters['team_id']);
                });
            }
        };

        $query = Course::where('active', self::ACTIVE)
            ->whereDate('end_date', '<', now())
            ->whereHas('passcourse', $unfinishedCondition)
            ->select('course_id', 'course_title', 'end_date', 'department_org_id');

        // กรองแผนก/ส่วนงาน/สายงาน ตามที่เลือกมาจากแถบค้นหา
        $this->applyOrgFilter($query, $filters);

        // ช่วงเวลา: จำกัดให้เหลือคอร์สที่ครบกำหนดในช่วงที่เลือก
        // (ยังคงเงื่อนไข end_date < now() ไว้ เพราะการ์ดนี้คือ "เลยกำหนดแล้ว" เท่านั้น
        // ถ้าเลือกช่วงที่เป็นอนาคต จะไม่มีคอร์สแสดง ซึ่งถูกต้องตามความหมายของการ์ด)
        $this->applyDateFilter($query, $filters, 'end_date');

        return $query
            ->withCount(['passcourse as unfinished_count' => $unfinishedCondition])
            ->orderByDesc('end_date')
            ->limit($limit)
            ->get()
            ->map(fn ($course) => [
                'course_id'   => $course->course_id,
                'title'       => $course->course_title,
                'deadline'    => $course->end_date,
                'unfinished'  => $course->unfinished_count,
            ]);
    }

    /**
     * ตาราง "การเรียนรู้ตามแผนก": ผู้เรียน / Completion Rate / คอร์สที่ต้องเรียนทั้งหมด
     * group ตาม department_org_id ของคอร์ส (ใช้ org filter เดียวกับ Overdue แต่ไม่กรองวันที่)
     *
     * สูตร Completion Rate (confirm แบบเบื้องต้น ยังไม่ใช่ของจริง 100%):
     *   passed_count / (total_courses * learner_count) * 100
     * - total_courses = จำนวนคอร์สทั้งหมดของแผนก (หลัง org filter)
     * - learner_count = จำนวนผู้เรียนของแผนก (หลัง team filter ถ้ามี)
     * - passed_count  = จำนวน record passcours ที่ status = pass ของผู้เรียนแผนกนี้
     *   ต่อคอร์สของแผนกนี้ (นับรวมทุกคน ไม่ใช่นับต่อคน)
     */
    private function getDepartmentLearning(array $filters)
    {
        $courseQuery = Course::where('active', 'y')
            ->select('course_id', 'department_org_id');

        $this->applyOrgFilter($courseQuery, $filters);

        $courses = $courseQuery->get();

        if ($courses->isEmpty()) {
            return collect();
        }

        $coursesByDept = $courses->groupBy('department_org_id');
        $departmentIds = $coursesByDept->keys()->filter()->values();

        if ($departmentIds->isEmpty()) {
            return collect();
        }

        $departmentTitles = Orgchart::whereIn('id', $departmentIds)->pluck('title', 'id');

        $usersQuery = DB::table('users')
            ->whereIn('department_org_id', $departmentIds)
            ->where('status', '1');

        $this->applyTeamFilter($usersQuery, $filters);

        $usersByDept = $usersQuery
            ->select('id', 'department_org_id')
            ->get()
            ->groupBy('department_org_id');

        $result = collect();

        foreach ($coursesByDept as $deptId => $deptCourses) {
            if (!$deptId) {
                continue;
            }

            $courseIds = $deptCourses->pluck('course_id');
            $totalCourses = $courseIds->count();

            $userIds = optional($usersByDept->get($deptId))->pluck('id') ?? collect();
            $learnerCount = $userIds->count();

            $passedCount = 0;

            if ($learnerCount > 0 && $totalCourses > 0) {
                $passedQuery = DB::table('passcours')
                    ->whereIn('passcours_cours', $courseIds)
                    ->whereIn('passcours_user', $userIds)
                    ->where('passcours_status', 'pass');

                // ช่วงเวลา: นับเฉพาะที่เรียนผ่านภายในช่วงที่เลือก
                $this->applyDateFilter($passedQuery, $filters, 'passcours_date');

                $passedCount = $passedQuery->count();
            }

            $denominator = $totalCourses * $learnerCount;
            $completionRate = $denominator > 0
                ? round(($passedCount / $denominator) * 100, 2)
                : 0;

            $result->push([
                'department_id'    => $deptId,
                'department'       => $departmentTitles[$deptId] ?? ('แผนก #' . $deptId),
                'learner_count'    => $learnerCount,
                'total_courses'    => $totalCourses,
                'passed_count'     => $passedCount,
                'completion_rate'  => $completionRate,
            ]);
        }

        return $result->sortByDesc('completion_rate')->values();
    }

    /**
     * หลักสูตรที่มีผู้เรียนมากที่สุด: นับผู้เรียน distinct ต่อคอร์สจากตาราง learn
     * เรียงจากมากไปน้อย เอา top N
     *
     * NOTE: ใช้ withCount + count(distinct user_id) แทนการ join แล้ว groupBy เอง
     * เพราะ connection นี้ตั้ง table prefix ('tbl_') ไว้ และ selectRaw ไม่ผ่านการเติม
     * prefix ให้ (แม้ใส่ alias ให้ตารางก็ไม่ช่วย เพราะ Laravel เติม prefix ให้ alias ด้วย
     * กลายเป็น "tbl_learn" as "tbl_l" ทำให้ raw ที่อ้าง l.user_id หา FROM-clause ไม่เจอ)
     * วิธีนี้ subquery มีตาราง learn อยู่ตัวเดียว จึงอ้าง user_id แบบไม่ต้องระบุตารางได้
     * ไม่ต้อง hardcode prefix ลงไปใน SQL
     *
     * whereHas('learn') กรองคอร์สที่ยังไม่มีคนเรียนออกตั้งแต่ใน SQL (สร้าง EXISTS)
     * ไม่ใช้ having บน alias เพราะ Postgres ไม่ยอมให้ HAVING อ้าง alias จาก SELECT
     * (ORDER BY อ้างได้ จึง orderByDesc('learner_count') ตรง ๆ ได้)
     */
    private function getPopularCourses(array $filters, int $limit = 5)
    {
        // เงื่อนไข "การเรียนที่นับ" ใช้ตัวเดียวกันทั้งใน whereHas และ withCount
        // ถ้าใช้เงื่อนไขไม่ตรงกัน จะเกิดเคสคอร์สติดอยู่ในลิสต์แต่ learner_count = 0
        $learnCondition = function ($query) use ($filters) {
            // ถ้าเลือกทีมมา ให้นับเฉพาะผู้เรียนในทีมนั้น (learn ผูกกับ user ผ่าน user_id)
            if (!empty($filters['team_id'])) {
                $query->whereIn('user_id', function ($sub) use ($filters) {
                    $sub->select('id')
                        ->from('users')
                        ->where('team_id', $filters['team_id']);
                });
            }

            // ช่วงเวลา: นับเฉพาะการเรียนที่เกิดในช่วงที่เลือก
            $this->applyDateFilter($query, $filters, 'learn_date');
        };

        $learnerCount = function ($query) use ($learnCondition) {
            $query->select(DB::raw('count(distinct user_id)'));
            $learnCondition($query);
        };

        $query = Course::where('active', self::ACTIVE)
            ->select('course_id', 'course_title', 'department_org_id')
            ->whereHas('learn', $learnCondition)
            ->withCount(['learn as learner_count' => $learnerCount]);

        // กรองแผนก/ส่วนงาน/สายงาน ตามที่เลือกมาจากแถบค้นหา
        $this->applyOrgFilter($query, $filters);

        return $query
            ->orderByDesc('learner_count')
            ->limit($limit)
            ->get()
            ->values()
            ->map(fn ($course, $index) => [
                'rank'          => $index + 1,
                'course_id'     => $course->course_id,
                'title'         => $course->course_title,
                'learner_count' => $course->learner_count,
            ]);
    }
}
