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
     * Entry point เดียวให้ Controller เรียก
     *
     * $filters รองรับ:
     *   - department_id  -> กรอง course_online.department_org_id ตรง ๆ
     *   - section_id / line_id -> กรอง "ระดับล่างกว่า department" ผ่าน org_course.orgchart_id
     *     (ถ้ามีทั้งคู่ ใช้ line_id เพราะลึกกว่า section_id)
     *   - team_id -> กรอง users.team_id เฉพาะ query ที่ join ไปถึง users อยู่แล้ว
     *   - date_from, date_to (ยังไม่ใช้ในรอบนี้)
     *   - search (ยังไม่ใช้ในรอบนี้)
     *
     * @return array แพ็กเก็ตข้อมูลแยกตามกลุ่มการ์ด/แถวใน view (1 key = 1 การ์ดหรือ 1 ตาราง)
     */

    public function getDepartment()
    {
        $dept = Orgchart::where('level',self::DEPT_LEVEL)->where('active',self::ACTIVE)->get();
        return $dept;
    }

    public function getTeam()
    {
        $team = Team::where('active',self::ACTIVE)->get();
        return $team;
    }

    /**
     * ดึงลูกของ orgchart node ใดๆ (ใช้กับ dropdown แบบ dynamic: department -> section -> line)
     *
     * NOTE: บางแผนกไม่มี "ไลน์" (level 5) จะกระโดดจาก section (level 4) ไปที่ตำแหน่ง
     * (level 6) เลย ฝั่ง frontend ต้องเช็ค level ของลูกที่ได้กลับมา ถ้าเป็น
     * POSITION_LEVEL แทน LINE_LEVEL ให้รู้ว่าแผนกนี้ไม่มีไลน์ (ดรอปดาวน์ "ไลน์ผลิต"
     * จะไม่มีตัวเลือกให้ ปล่อยเป็น "ทั้งหมด" ไปก่อน เพราะ filter ยังไม่รองรับ position_id)
     */
    public function getOrgChildren($parentId)
    {
        return Orgchart::where('parent_id', $parentId)
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get(['id', 'title', 'level']);
    }

    public function getDashboardData(array $filters = []): array
    {
        return [
            // แถวที่ 1: การ์ดสรุปตัวเลข 4 ใบ
            'overview' => $this->getOverviewStats($filters),

            // แถวที่ 3 การ์ดที่ 1: คอร์สที่ต้องติดตาม
            'overdueCourses' => $this->getOverdueCourses($filters),

            // แถวที่ 4 ตารางที่ 1: การเรียนรู้ตามแผนก
            'departmentLearning' => $this->getDepartmentLearning($filters),

            // แถวที่ 4 การ์ดที่ 2: หลักสูตรที่มีผู้เรียนมากที่สุด
            // 'popularCourses' => $this->getPopularCourses($filters),
        ];
    }

    /**
     * Filter helper กลาง: apply เงื่อนไข org (department/section/line) ให้ query
     * ที่มีคอลัมน์ course_id / department_org_id (ใช้ได้ทั้ง Eloquent builder ของ Course
     * และ DB::table('course_online')->... เพราะ interface where()/whereIn() เหมือนกัน)
     *
     * Logic ตามที่ confirm ไว้ (ดู _PLAN_admin_dashboard_filters.md):
     * - ไม่เลือกอะไร -> ไม่กรอง
     * - เลือก section_id หรือ line_id (ระดับลึกกว่า department) -> ไปดูที่ org_course.orgchart_id
     *   แทน department_org_id (ใช้ line_id ก่อนถ้ามีทั้งคู่ เพราะลึกกว่า)
     * - เลือกแค่ department_id -> กรอง department_org_id ตรง ๆ
     */
    private function applyOrgFilter($query, array $filters)
    {
        $deepOrgId = $filters['line_id'] ?? $filters['section_id'] ?? null;

        if (!empty($deepOrgId)) {
            $courseIds = DB::table('org_course')
                ->where('orgchart_id', $deepOrgId)
                ->pluck('course_id');

            $query->whereIn('course_id', $courseIds);
        } elseif (!empty($filters['department_id'])) {
            $query->where('department_org_id', $filters['department_id']);
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
     * แถวที่ 1 (4 การ์ด): คอร์สทั้งหมด / เนื้อหาทั้งหมด / ผู้ใช้ทั้งหมด / รออนุมัติ
     *
     * ใช้ DB::table()->count() แทน raw SQL string เพราะ connection 'pgsql'
     * ตั้ง prefix ('tbl_') ไว้ใน config/database.php — query builder/Eloquent
     * จะเติม prefix ให้เองตอน compile แต่ raw SQL string ไม่ผ่านการเติม prefix นี้
     * (นี่คือสาเหตุของ error "relation course_online does not exist" ก่อนหน้านี้)
     *
     * แลกกับ 4 round-trip แทน 1 แต่ปลอดภัยกว่าและ apply filter ต่อได้ง่ายกว่า raw SQL
     *
     * NOTE: ยังไม่ apply $filters ที่นี่ เพราะการ์ดสรุประดับบนสุดมักหมายถึง
     * "ทั้งระบบ" — ถ้าต้องการให้ตัวเลขนี้ไหวตามแผนก/ทีมที่เลือกด้วย
     * ต้องเพิ่ม WHERE ตามคีย์ที่เกี่ยวข้องในแต่ละ query (แจ้งได้ ผมจะเติมให้)
     */
    private function getOverviewStats(array $filters): array
    {
        return [
            'total_courses'     => DB::table('course_online')->where('active', 'y')->count(),
            'total_files'       => DB::table('file')->where('active', 'y')->count(),
            'total_users'       => DB::table('users')->where('status', '1')->count(),
            'pending_approvals' => DB::table('log_approve')->count(),
        ];
    }

    /**
     * คอร์สที่ต้องติดตาม (Overdue): คอร์สที่ end_date ผ่านมาแล้ว
     * และยังมีผู้เรียนที่ยังไม่ผ่าน (passcourse ยัง pass ไม่ครบ)
     * เรียงจาก end_date ที่ใกล้วันปัจจุบันมากที่สุดก่อน (overdue ล่าสุด) เอามา 5 อันดับแรก
     *
     * Org filter: department_id / section_id / line_id กรองที่ตัวคอร์สผ่าน applyOrgFilter()
     * team_id: กรองเฉพาะ "ผู้เรียนที่ยังไม่ผ่าน" ที่นับ/แสดง (ไม่กรองว่าคอร์สจะติด overdue หรือไม่
     * จากทีมอื่น เพราะ unfinished_count ต้องนับตามทีมที่เลือกเท่านั้น)
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

        $query = Course::where('active', 'y')
            ->whereDate('end_date', '<', now())
            ->whereHas('passcourse', $unfinishedCondition)
            ->select('course_id', 'course_title', 'end_date', 'department_org_id');

        $this->applyOrgFilter($query, $filters);

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
                $passedCount = DB::table('passcours')
                    ->whereIn('passcours_cours', $courseIds)
                    ->whereIn('passcours_user', $userIds)
                    ->where('passcours_status', 'pass')
                    ->count();
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
     * หลักสูตรที่มีผู้เรียนมากที่สุด: จำนวนผู้เรียน distinct ต่อคอร์ส (นับจาก learn)
     * เรียงจากมากไปน้อย จำกัด top N — group ที่ DB ครั้งเดียว
     */
    // private function getPopularCourses(array $filters, int $limit = 5)
    // {
    //     return Learn::join('course_online as c', 'c.course_id', '=', 'learn.course_id')
    //         ->select('c.course_id', 'c.course_title')
    //         ->selectRaw('COUNT(DISTINCT learn.user_id) as learner_count')
    //         ->groupBy('c.course_id', 'c.course_title')
    //         ->orderByDesc('learner_count')
    //         ->limit($limit)
    //         ->get()
    //         ->map(function ($row, $index) {
    //             return [
    //                 'rank'          => $index + 1,
    //                 'course_id'     => $row->course_id,
    //                 'title'         => $row->course_title,
    //                 'learner_count' => $row->learner_count,
    //             ];
    //         });
    // }
}
