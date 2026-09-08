<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Orgchart;
use Illuminate\Support\Facades\DB;

class ManagementDashboardService
{
    const DEPT_LEVEL = '3';
    const SECTION_LEVEL = '4';
    const LINE_LEVEL = '5';
    const POSITION_LEVEL = '6';

    const ACTIVE = 'y';

    /**
     * Cache ผลลัพธ์ของ getDescendantOrgIds() ต่อ org id (มีอายุแค่ 1 request
     * เพราะ service ถูกสร้าง instance ใหม่ทุก request) — ดูเหตุผลเต็ม ๆ ที่
     * getDescendantOrgIds()
     */
    private array $descendantOrgIdsCache = [];

    /**
     * โครงต้นไม้ org chart ดิบ (parent_id => [child_id, ...]) โหลดครั้งเดียว
     * ต่อ request แล้ว traverse ในหน่วยความจำแทนการ query ทีละ node — ดู
     * loadOrgChildrenMap() / getDescendantOrgIds()
     */
    private ?array $orgChildrenMap = null;

    /**
     * ข้อมูล org node ดิบ เก็บเป็น id => ['title'=>.., 'parent_id'=>..,
     * 'level'=>..] โหลดพร้อมกับ $orgChildrenMap ในคราวเดียวกัน (query
     * เดียวกัน ไม่ query เพิ่ม) ใช้สำหรับเดินขึ้นหา ancestor (เช่น หา
     * แผนกของ section/line) — ดู getAncestorDepartmentTitle()
     */
    private ?array $orgNodesById = null;

    /**
     * ============================================================
     * Master Data
     * ============================================================
     */

    public function getDepartments()
    {
        return Orgchart::where('level', self::DEPT_LEVEL)
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    public function getOrgChildren($parentId)
    {
        return Orgchart::where('parent_id', $parentId)
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get([
                'id',
                'title',
                'level'
            ]);
    }

    public function getTeams()
    {
        return DB::table('team')
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get();
    }

    /**
     * ============================================================
     * Dashboard
     * ============================================================
     */

    public function getDashboardData(array $filters = [])
    {
        /**
         * เปิด Section 2-4 กลับมาแล้ว หลังแก้ต้นตอที่ทำให้หน้าโหลดไม่ขึ้น:
         * getDescendantOrgIds() ตอนนี้ cache ผลลัพธ์ต่อ org id ไว้ในระดับ
         * request (ไม่ยิง query recursive ซ้ำทุกครั้งที่ถูกเรียกด้วย id เดิม)
         * และกัน infinite loop ถ้า org tree มี parent_id วนเป็นวงกลม — ดู
         * รายละเอียดที่ getDescendantOrgIds() ไม่ได้แตะ logic การคำนวณเดิมของ
         * เมธอดด้านล่างนี้ (ยกเว้นจุดที่คอมเมนต์ไว้เฉพาะจุด)
         *
         * ข้อยกเว้น: skillGapTeams ยังเรียก getTeamSkillGap() ที่คืนค่าว่างอยู่
         * เหมือนเดิม เพราะยังไม่มี skill matrix table จริงให้อ้างอิง (ดูหมายเหตุ
         * ที่เมธอดนั้น) การ์ดนี้จะขึ้น "ไม่พบข้อมูล" แทนตัวเลข mock ที่เคยฝังตรง
         * ใน blade
         */
        return [

            // Section 1
            'summary' => $this->getTrainingSummary($filters),

            // Section 2
            'lineCompletion' => $this->getLineCompletion($filters),

            'sectionPassRate' => $this->getSectionPassRate($filters),

            // Pass Rate รวม สำหรับตัวเลขกลาง donut ของการ์ด "Pass Rate ของแต่ละ
            // Section" — แยกจาก summary.pass_rate ที่ตัดออกไปแล้วตามที่ตกลงกับ
            // ผู้ใช้ไว้ก่อนหน้า (ดูหมายเหตุที่ getTrainingSummary())
            'overallPassRate' => $this->getOverallPassRate($filters),

            'failedCourses' => $this->getTopFailedCourses($filters),

            // Section 3
            'newEmployees' => $this->getNewEmployeeProgress($filters),

            'skillGapTeams' => $this->getTeamSkillGap($filters),

            // Section 4
            'departmentComparison' => $this->getDepartmentComparison($filters),

            'monthlyTrend' => $this->getMonthlyTrend($filters),
        ];
    }

    /**
     * ============================================================
     * 1. Training Summary
     *
     * พนักงานทั้งหมด
     * Completion Rate
     * Course Overdue
     * ต้องสอบซ่อม
     *
     * (ตัด Pass Rate ออกตามที่ผู้ใช้ยืนยัน เพราะซ้ำซ้อนกับ Completion Rate)
     * ============================================================
     */

    private function getTrainingSummary(array $filters)
    {
        $usersQuery = DB::table('users')
            ->where('status', '1');

        $this->applyUserFilter($usersQuery, $filters);

        $totalUsers = $usersQuery->count();

        /**
         * Course ที่อยู่ใน scope
         */
        $courseIds = $this->getCourseIds($filters);

        $totalCourses = count($courseIds);

        /**
         * Completion
         *
         * จำนวน passcours ที่ผ่าน
         * เทียบกับจำนวน user x course
         *
         * (ใช้สูตรเดิมตามที่ผู้ใช้ยืนยันให้ลองใช้สูตรนี้ก่อน)
         */
        $passedCourses = 0;

        if ($totalUsers > 0 && $totalCourses > 0) {

            $userIds = $usersQuery
                ->pluck('id');

            $passedCourses = DB::table('passcours')
                ->whereIn('passcours_cours', $courseIds)
                ->whereIn('passcours_user', $userIds)
                ->where('passcours_status', 'pass')
                ->count();
        }

        $totalRequired = $totalUsers * $totalCourses;

        $completionRate = $totalRequired > 0
            ? round(($passedCourses / $totalRequired) * 100, 2)
            : 0;

        /**
         * Course Overdue
         *
         * ใช้นิยามเดียวกับ AdminDashboardService::getOverdueCourses() ตามที่ผู้ใช้ยืนยัน:
         * นับเฉพาะคอร์สที่ end_date ผ่านไปแล้ว (active='y') และ "ยังมีคนเรียนไม่จบค้างอยู่"
         * (มี passcourse ที่ passcours_status != 'pass' อย่างน้อย 1 คน) ไม่ใช่นับคอร์ส
         * หมดเขตทั้งหมดแบบเดิม
         *
         * ใช้ whereHas (EXISTS subquery) แทน having() บน alias เพราะ PostgreSQL
         * ไม่ยอมให้ HAVING อ้าง alias จาก SELECT ได้ (เหตุผลเดียวกับที่ AdminDashboardService ใช้)
         */
        $unfinishedCondition = function ($q) use ($filters) {
            $q->where('passcours_status', '!=', 'pass');

            if (!empty($filters['team_id'])) {
                $q->whereHas('user', function ($uq) use ($filters) {
                    $uq->where('team_id', $filters['team_id']);
                });
            }
        };

        $overdueQuery = Course::where('active', self::ACTIVE)
            ->whereDate('end_date', '<', now())
            ->whereHas('passcourse', $unfinishedCondition);

        $this->applyCourseFilter($overdueQuery, $filters);
        $this->applyDateFilter($overdueQuery, $filters, 'end_date');

        $overdueCount = $overdueQuery->count();

        /**
         * ต้องสอบซ่อม
         *
         * นับจาก coursescore.score_status = 'fail' (คนที่สอบตก = ต้องสอบซ่อม)
         * ตามที่ผู้ใช้ยืนยัน — ค่า 'retry' ที่ใช้อยู่เดิมไม่มีอยู่จริงในระบบ
         * (ค่า score_status ที่ใช้จริงมีแค่ pass/fail/wait) ทำให้ query เดิมได้ 0 เสมอ
         */
        $retryQuery = DB::table('coursescore')
            ->where('score_status', 'fail');

        $this->applyUserJoinFilter(
            $retryQuery,
            $filters
        );

        $retryCount = $retryQuery
            ->distinct('user_id')
            ->count('user_id');

        return [
            'total_users' => $totalUsers,

            'completion_rate' => $completionRate,

            'overdue_courses' => $overdueCount,

            'retry_users' => $retryCount,
        ];
    }

    /**
     * ============================================================
     * 2. Completion Rate ของแต่ละ Line
     * ============================================================
     */

    private function getLineCompletion(array $filters)
    {
        $lines = Orgchart::where('level', self::LINE_LEVEL)
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get();

        $result = collect();

        /**
         * ย้าย getDescendantOrgIds() ของ department_id/section_id ออกมานอก
         * loop เพราะค่า filter ไม่เปลี่ยนต่อรอบ line (เดิมเรียกซ้ำทุก line
         * ในลูป) — getDescendantOrgIds() มี cache แล้วก็จริง แต่ hoist ตรงนี้
         * ให้ชัดเจนไปเลยว่าเป็นค่าคงที่ตลอด loop
         */
        $departmentOrgIds = !empty($filters['department_id'])
            ? $this->getDescendantOrgIds($filters['department_id'])
            : null;

        $filterSectionOrgIds = !empty($filters['section_id'])
            ? $this->getDescendantOrgIds($filters['section_id'])
            : null;

        foreach ($lines as $line) {

            /**
             * ถ้าเลือก Department
             * ต้องเช็คว่า Line อยู่ใต้ Department หรือไม่
             */
            if ($departmentOrgIds !== null &&
                !in_array($line->id, $departmentOrgIds)) {
                continue;
            }

            if ($filterSectionOrgIds !== null &&
                !in_array($line->id, $filterSectionOrgIds)) {
                continue;
            }

            if (!empty($filters['line_id']) &&
                $filters['line_id'] != $line->id) {

                continue;
            }

            $orgIds = $this->getDescendantOrgIds($line->id);

            $usersQuery = DB::table('users')
                ->whereIn('org_id', $orgIds)
                ->where('status', '1');

            if (!empty($filters['team_id'])) {
                $usersQuery->where(
                    'team_id',
                    $filters['team_id']
                );
            }

            $userIds = $usersQuery->pluck('id');

            $courseIds = $this->getCourseIds([
                'line_id' => $line->id
            ]);

            $total = count($userIds) * count($courseIds);

            $passed = 0;

            if ($total > 0) {

                $passed = DB::table('passcours')
                    ->whereIn(
                        'passcours_user',
                        $userIds
                    )
                    ->whereIn(
                        'passcours_cours',
                        $courseIds
                    )
                    ->where(
                        'passcours_status',
                        'pass'
                    )
                    ->count();
            }

            $completion = $total > 0
                ? round(($passed / $total) * 100, 2)
                : 0;

            $result->push([
                'id' => $line->id,
                'name' => $line->title,
                'department' => $this->getAncestorDepartmentTitle($line->id),
                'completion_rate' => $completion,
                'trend' => 0,
            ]);
        }

        return $result;
    }

    /**
     * ============================================================
     * 3. Pass Rate ของแต่ละ Section
     * ============================================================
     */

    private function getSectionPassRate(array $filters)
    {
        $sections = Orgchart::where('level', self::SECTION_LEVEL)
            ->where('active', self::ACTIVE)
            ->orderBy('title')
            ->get();

        $result = collect();

        /**
         * เดิมเมธอดนี้ไม่เช็ค department_id/section_id เลย (ต่างจาก
         * getLineCompletion) ทำให้เลือกกรองแผนกแล้ว section ของแผนกอื่นยัง
         * หลุดเข้ามาคำนวณด้วย เพิ่ม scope check ให้เป็น pattern เดียวกับ
         * getLineCompletion()
         */
        $departmentOrgIds = !empty($filters['department_id'])
            ? $this->getDescendantOrgIds($filters['department_id'])
            : null;

        foreach ($sections as $section) {

            if ($departmentOrgIds !== null &&
                !in_array($section->id, $departmentOrgIds)) {
                continue;
            }

            if (!empty($filters['section_id']) &&
                $filters['section_id'] != $section->id) {
                continue;
            }

            $orgIds = $this->getDescendantOrgIds(
                $section->id
            );

            $usersQuery = DB::table('users')
                ->whereIn('org_id', $orgIds)
                ->where('status', '1');

            if (!empty($filters['team_id'])) {
                $usersQuery->where(
                    'team_id',
                    $filters['team_id']
                );
            }

            $userIds = $usersQuery->pluck('id');

            $courseIds = $this->getCourseIds([
                'section_id' => $section->id
            ]);

            $pass = DB::table('passcours')
                ->whereIn('passcours_user', $userIds)
                ->whereIn('passcours_cours', $courseIds)
                ->where('passcours_status', 'pass')
                ->count();

            $attempt = DB::table('passcours')
                ->whereIn('passcours_user', $userIds)
                ->whereIn('passcours_cours', $courseIds)
                ->count();

            $rate = $attempt > 0
                ? round(($pass / $attempt) * 100, 2)
                : 0;

            $result->push([
                'id' => $section->id,
                'name' => $section->title,
                'department' => $this->getAncestorDepartmentTitle($section->id),
                'pass_rate' => $rate,
            ]);
        }

        return $result;
    }

    /**
     * ============================================================
     * 3.1 Pass Rate รวม (ตัวเลขกลาง donut ของการ์ด "Pass Rate ของแต่ละ
     * Section")
     *
     * เดิม blade อ้าง $dashboard['summary']['pass_rate'] ซึ่งไม่มีจริงแล้ว
     * (ตัดออกจาก summary ไปตามที่ตกลงกับผู้ใช้ไว้ก่อนหน้า เพราะซ้ำซ้อนกับ
     * completion_rate ในบริบทของ Section 1 — ดู getTrainingSummary()) เลย
     * ขึ้น 0% ตลอด เพิ่มเมธอดนี้แยกต่างหากแทนการใส่กลับเข้า summary เพื่อไม่
     * ให้ขัดกับการตัดสินใจเดิม — เป็นคนละตัวเลขกับค่าเฉลี่ยของ % ต่อ section
     * ด้านล่าง (ตรงนี้คือ pass รวม/attempt รวมทั้งหมดในขอบเขตปัจจุบัน)
     * ============================================================
     */

    private function getOverallPassRate(array $filters)
    {
        $usersQuery = DB::table('users')
            ->where('status', '1');

        $this->applyUserFilter($usersQuery, $filters);

        $userIds = $usersQuery->pluck('id');

        $courseIds = $this->getCourseIds($filters);

        $pass = DB::table('passcours')
            ->whereIn('passcours_user', $userIds)
            ->whereIn('passcours_cours', $courseIds)
            ->where('passcours_status', 'pass')
            ->count();

        $attempt = DB::table('passcours')
            ->whereIn('passcours_user', $userIds)
            ->whereIn('passcours_cours', $courseIds)
            ->count();

        return $attempt > 0
            ? round(($pass / $attempt) * 100, 2)
            : 0;
    }

    /**
     * ============================================================
     * 4. Top 5 หลักสูตรที่ไม่ผ่านมากที่สุด
     * ============================================================
     */

    private function getTopFailedCourses(array $filters, int $limit = 5)
    {
        $query = DB::table('passcours')
            ->join(
                'course_online as c',
                'passcours.passcours_cours',
                '=',
                'c.course_id'
            )
            ->where(
                'passcours.passcours_status',
                '!=',
                'pass'
            )
            ->where(
                'c.active',
                self::ACTIVE
            );

        $this->applyCourseFilter($query, $filters, 'c');

        return $query
            ->select(
                'c.course_id',
                'c.course_title'
            )
            /**
             * selectRaw() ไม่ผ่าน query builder grammar เลย จึงไม่ได้ table
             * prefix (เช่น tbl_) ที่ตั้งไว้ใน config/database.php ให้อัตโนมัติ
             * เหมือน ->where()/->join() ตัวอื่นในเมธอดนี้ (นั่นคือสาเหตุที่ SQL
             * ที่ generate ออกมาก่อนหน้านี้มี tbl_passcours ทุกจุด ยกเว้นตรงนี้
             * จุดเดียวที่ยังเป็น passcours เฉย ๆ) ตัดชื่อ table ออกจาก column
             * referenceไปเลยแทนที่จะไป hardcode prefix — ไม่ชนกับ c.* เพราะ
             * course_online ไม่มีคอลัมน์ชื่อ passcours_user จะได้ไม่ต้องพึ่ง
             * prefix ตรงนี้อีกไม่ว่า config จะตั้งเป็นอะไรก็ตาม
             */
            ->selectRaw(
                'COUNT(DISTINCT passcours_user) as failed_count'
            )
            ->groupBy(
                'c.course_id',
                'c.course_title'
            )
            ->orderByDesc('failed_count')
            ->limit($limit)
            ->get()
            ->values()
            ->map(function ($row, $index) {

                return [
                    'rank' => $index + 1,
                    'course_id' => $row->course_id,
                    'title' => $row->course_title,
                    'failed_count' => $row->failed_count,
                ];
            });
    }

    /**
     * ============================================================
     * 5. พนักงานใหม่
     * ============================================================
     */

    private function getNewEmployeeProgress(array $filters)
    {
        /**
         * field วันที่เริ่มงาน: users.work_start (ยืนยันจาก DB จริงแล้ว — เดิม
         * สมมติไว้เป็น start_date ซึ่งไม่มีคอลัมน์นี้จริง)
         */
        $query = DB::table('users')
            ->where('status', '1')
            ->whereNotNull('work_start')
            ->where(
                'work_start',
                '>=',
                now()->subDays(120)
            );

        $this->applyUserFilter($query, $filters);

        $users = $query->get();

        $result = [
            '30' => 0,
            '60' => 0,
            '90' => 0,
            '120' => 0,
            'over120' => 0,
        ];

        foreach ($users as $user) {

            $days = now()->diffInDays(
                $user->work_start
            );

            if ($days < 30) {
                $result['30']++;
            } elseif ($days < 60) {
                $result['60']++;
            } elseif ($days < 90) {
                $result['90']++;
            } elseif ($days <= 120) {
                $result['120']++;
            } else {
                $result['over120']++;
            }
        }

        return $result;
    }

    /**
     * ============================================================
     * 6. Team Skill Gap
     * ============================================================
     */

    private function getTeamSkillGap(array $filters)
    {
        /**
         * ส่วนนี้ควรผูกกับ Skill Matrix จริงของระบบ
         *
         * ถ้ายังไม่มี table skill matrix
         * ยังไม่ควร hard-code ตัวเลข
         *
         * TODO ยังต้องตัดสินใจก่อนว่า "skill gap" คำนวณจากอะไร (ผูกกับ
         * skill matrix จริง หรือจะ derive จาก completion/pass rate ต่อทีม
         * ไปพลาง ๆ ก่อน) — blade (adminmanagement.blade.php) แก้ให้วนตาม
         * collection นี้แล้ว แต่ละแถวที่คืนควรมีรูปแบบ:
         * ['rank' => int, 'team_id' => ..., 'team' => string, 'value' => float]
         */

        return collect();
    }

    /**
     * ============================================================
     * 7. Department Comparison
     * ============================================================
     */

    private function getDepartmentComparison(array $filters)
    {
        $departments = Orgchart::where(
                'level',
                self::DEPT_LEVEL
            )
            ->where(
                'active',
                self::ACTIVE
            )
            ->orderBy('title')
            ->get();

        $result = collect();

        foreach ($departments as $department) {

            if (!empty($filters['department_id']) &&
                $filters['department_id'] != $department->id) {
                continue;
            }

            $orgIds = $this->getDescendantOrgIds(
                $department->id
            );

            $usersQuery = DB::table('users')
                ->whereIn('org_id', $orgIds)
                ->where('status', '1');

            if (!empty($filters['team_id'])) {
                $usersQuery->where(
                    'team_id',
                    $filters['team_id']
                );
            }

            $userIds = $usersQuery->pluck('id');

            $courseIds = $this->getCourseIds([
                'department_id' => $department->id
            ]);

            $total = count($userIds) * count($courseIds);

            $passed = DB::table('passcours')
                ->whereIn(
                    'passcours_user',
                    $userIds
                )
                ->whereIn(
                    'passcours_cours',
                    $courseIds
                )
                ->where(
                    'passcours_status',
                    'pass'
                )
                ->count();

            $attempt = DB::table('passcours')
                ->whereIn(
                    'passcours_user',
                    $userIds
                )
                ->whereIn(
                    'passcours_cours',
                    $courseIds
                )
                ->count();

            $completion = $total > 0
                ? round(($passed / $total) * 100, 2)
                : 0;

            $passRate = $attempt > 0
                ? round(($passed / $attempt) * 100, 2)
                : 0;

            $overdue = DB::table('course_online')
                ->where('active', 'y')
                ->whereDate(
                    'end_date',
                    '<',
                    now()
                )
                ->where(
                    'department_org_id',
                    $department->id
                )
                ->count();

            /**
             * ต้องสอบซ่อม ต่อแผนก — ใช้นิยามเดียวกับ getTrainingSummary()
             * (coursescore.score_status = 'fail')
             */
            $retryCount = DB::table('coursescore')
                ->whereIn('user_id', $userIds)
                ->where('score_status', 'fail')
                ->distinct('user_id')
                ->count('user_id');

            $result->push([
                'department_id' => $department->id,
                'department' => $department->title,

                'employees' => count($userIds),

                'completion_rate' => $completion,

                'pass_rate' => $passRate,

                'overdue' => $overdue,

                'retry' => $retryCount,

                // ยังผูกกับ skill matrix จริงไม่ได้ (เหตุผลเดียวกับ
                // getTeamSkillGap()) คงไว้ที่ 0 ก่อน
                'skill_gap' => 0,

                // ยังไม่ได้ตัดสินใจว่า "trend" เทียบกับ period ไหน (เดือน
                // ก่อนหน้า / ช่วงเดียวกันปีก่อน ฯลฯ) คงไว้ที่ 0 ก่อน
                'completion_trend' => 0,
                'pass_trend' => 0,
            ]);
        }

        return $result;
    }

    /**
     * ============================================================
     * 8. Monthly Trend
     * ============================================================
     */

    private function getMonthlyTrend(array $filters)
    {
        $result = collect();

        // เดิมเรียก getCourseIds() ซ้ำทุกรอบเดือน (6 ครั้ง) ทั้งที่ $filters
        // ไม่เปลี่ยนระหว่าง loop — ย้ายออกมานอก loop เรียกครั้งเดียวพอ
        $courseIds = $this->getCourseIds($filters);

        for ($i = 5; $i >= 0; $i--) {

            $date = now()
                ->subMonths($i);

            $start = $date
                ->copy()
                ->startOfMonth();

            $end = $date
                ->copy()
                ->endOfMonth();

            $query = DB::table('passcours')
                ->whereIn(
                    'passcours_cours',
                    $courseIds
                )
                ->whereBetween(
                    'created_at',
                    [$start, $end]
                );

            $total = $query->count();

            $passed = (clone $query)
                ->where(
                    'passcours_status',
                    'pass'
                )
                ->count();

            $passRate = $total > 0
                ? round(($passed / $total) * 100, 2)
                : 0;

            $result->push([
                'month' => $date->format('M Y'),
                'completion_rate' => $passRate,
                'pass_rate' => $passRate,
                'retry' => 0,
            ]);
        }

        return $result;
    }

    /**
     * ============================================================
     * Helpers
     * ============================================================
     */

    private function getCourseIds(array $filters)
    {
        $query = DB::table('course_online')
            ->where('active', self::ACTIVE);

        $this->applyCourseFilter(
            $query,
            $filters
        );

        return $query
            ->pluck('course_id')
            ->toArray();
    }

    private function applyCourseFilter(
        $query,
        array $filters,
        string $alias = ''
    ) {
        $prefix = $alias
            ? $alias . '.'
            : '';

        $deepOrgId =
            $filters['line_id']
            ?? $filters['section_id']
            ?? null;

        if ($deepOrgId) {

            $courseIds = DB::table('org_course')
                ->where(
                    'orgchart_id',
                    $deepOrgId
                )
                ->pluck('course_id');

            $query->whereIn(
                $prefix . 'course_id',
                $courseIds
            );

        } elseif (!empty($filters['department_id'])) {

            $query->where(
                $prefix . 'department_org_id',
                $filters['department_id']
            );
        }

        return $query;
    }

    private function applyUserFilter(
        $query,
        array $filters,
        string $alias = ''
    ) {
        $prefix = $alias
            ? $alias . '.'
            : '';

        $orgId =
            $filters['line_id']
            ?? $filters['section_id']
            ?? $filters['department_id']
            ?? null;

        if ($orgId) {

            $orgIds = $this->getDescendantOrgIds(
                $orgId
            );

            $query->whereIn(
                $prefix . 'org_id',
                $orgIds
            );
        }

        if (!empty($filters['team_id'])) {

            $query->where(
                $prefix . 'team_id',
                $filters['team_id']
            );
        }

        return $query;
    }

    /**
     * Filter helper: กรอง date_from/date_to (ใช้ pattern เดียวกับ
     * AdminDashboardService::applyDateFilter()) ไม่มีคีย์นี้ = ไม่กรองวันที่
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

    private function applyUserJoinFilter(
        $query,
        array $filters
    ) {
        $query->join(
            'users',
            'coursescore.user_id',
            '=',
            'users.id'
        );

        $this->applyUserFilter(
            $query,
            $filters,
            'users'
        );

        return $query;
    }

    /**
     * คืน id ของ org node ทั้งหมดที่อยู่ใต้ $parentId (รวม $parentId เอง)
     *
     * รอบแรกที่แก้ (เพิ่มแค่ cache ต่อ id + กัน infinite loop) ยังไม่พอ:
     * cache ช่วยเฉพาะตอนเรียกซ้ำด้วย "id เดิม" แต่ getLineCompletion() /
     * getSectionPassRate() / getDepartmentComparison() loop แล้วเรียกด้วย
     * id ที่ "ต่างกันทุกรอบ" (id ของแต่ละ line/section/department เอง) —
     * ยิ่งตอนนี้ filter เป็น "ทั้งหมด" (ไม่กรอง department/section/line เลย)
     * ทุก line/section/department ในระบบจะถูกวนครบ แต่ละตัวยัง query หา
     * ลูกของตัวเองแบบ recursive ใหม่หมด ทำให้ยังช้า/timeout อยู่
     *
     * รอบนี้แก้จริง: โหลด org chart ทั้งก้อนมาครั้งเดียว (query เดียว) แล้ว
     * สร้างเป็น map ในหน่วยความจำ (parent_id => [child_id, ...]) จากนั้น
     * traverse หา descendant จาก map นี้แทนการ query ทีละ node — ไม่ว่าจะ
     * เรียกด้วย id กี่ตัวก็ตาม จะยิง query จริงแค่ครั้งเดียวทั้ง request
     *
     * ยังกัน infinite loop ด้วย $visited เหมือนเดิม เผื่อ parent_id ใน
     * ข้อมูลวนเป็นวงกลม
     */
    private function getDescendantOrgIds($parentId): array
    {
        $parentId = (int) $parentId;

        if (isset($this->descendantOrgIdsCache[$parentId])) {
            return $this->descendantOrgIdsCache[$parentId];
        }

        $childrenMap = $this->loadOrgChildrenMap();

        return $this->descendantOrgIdsCache[$parentId] =
            $this->collectDescendantOrgIds($parentId, $childrenMap, []);
    }

    /**
     * โหลด org chart ที่ active ทั้งหมดมาครั้งเดียว แล้วจัดเป็น 2 โครงสร้าง
     * จาก query เดียวกัน (ไม่ query ซ้ำ):
     * - $orgChildrenMap: parent_id => [child_id, ...] ใช้หา descendant
     * - $orgNodesById: id => ['title'=>.., 'parent_id'=>.., 'level'=>..]
     *   ใช้เดินขึ้นหา ancestor (getAncestorDepartmentTitle())
     */
    private function loadOrgChildrenMap(): array
    {
        $this->loadOrgChartOnce();

        return $this->orgChildrenMap;
    }

    private function loadOrgNodesById(): array
    {
        $this->loadOrgChartOnce();

        return $this->orgNodesById;
    }

    private function loadOrgChartOnce(): void
    {
        if ($this->orgChildrenMap !== null) {
            return;
        }

        $childrenMap = [];
        $nodesById = [];

        $rows = Orgchart::where('active', self::ACTIVE)
            ->get(['id', 'parent_id', 'title', 'level']);

        foreach ($rows as $row) {

            $id = (int) $row->id;
            $parentId = (int) $row->parent_id;

            $childrenMap[$parentId][] = $id;

            $nodesById[$id] = [
                'title' => $row->title,
                'parent_id' => $parentId,
                'level' => $row->level,
            ];
        }

        $this->orgChildrenMap = $childrenMap;
        $this->orgNodesById = $nodesById;
    }

    /**
     * เดินขึ้นจาก $nodeId ไปหา ancestor ที่ level ตรงกับ DEPT_LEVEL แล้วคืน
     * title ของ department นั้น (คืน null ถ้าไม่เจอ) — ใช้กำกับชื่อแผนกไว้
     * ข้าง section/line ตอน filter แผนกเป็น "ทั้งหมด" กันงงเวลาชื่อซ้ำกันข้าม
     * แผนก เดินจาก $orgNodesById ในหน่วยความจำ ไม่มี query เพิ่ม กัน
     * infinite loop ด้วย $visited เหมือนจุดอื่น ๆ
     */
    private function getAncestorDepartmentTitle($nodeId): ?string
    {
        $nodes = $this->loadOrgNodesById();

        $currentId = (int) $nodeId;
        $visited = [];

        while (isset($nodes[$currentId])) {

            if (in_array($currentId, $visited, true)) {
                return null;
            }

            $visited[] = $currentId;

            $node = $nodes[$currentId];

            if ((string) $node['level'] === self::DEPT_LEVEL) {
                return $node['title'];
            }

            if (!$node['parent_id']) {
                return null;
            }

            $currentId = $node['parent_id'];
        }

        return null;
    }

    private function collectDescendantOrgIds(
        $parentId,
        array $childrenMap,
        array $visited
    ): array {
        $parentId = (int) $parentId;

        if (in_array($parentId, $visited, true)) {
            return [];
        }

        $visited[] = $parentId;

        $result = [$parentId];

        $children = $childrenMap[$parentId] ?? [];

        foreach ($children as $childId) {

            $result = array_merge(
                $result,
                $this->collectDescendantOrgIds(
                    $childId,
                    $childrenMap,
                    $visited
                )
            );
        }

        return $result;
    }
}