<?php

namespace App\Services;

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
        return [

            // Section 1
            'summary' => $this->getTrainingSummary($filters),

            // Section 2
            'lineCompletion' => $this->getLineCompletion($filters),

            'sectionPassRate' => $this->getSectionPassRate($filters),

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
     * Pass Rate
     * Course Overdue
     * ต้องสอบซ่อม
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
         * Pass Rate
         *
         * ใช้ผลสอบที่มี score/pass status
         */
        $passQuery = DB::table('passcours')
            ->whereIn('passcours_cours', $courseIds)
            ->where('passcours_status', 'pass');

        if ($totalUsers > 0) {
            $passQuery->whereIn(
                'passcours_user',
                $usersQuery->pluck('id')
            );
        }

        $passCount = $passQuery->count();

        $attemptQuery = DB::table('passcours')
            ->whereIn('passcours_cours', $courseIds);

        if ($totalUsers > 0) {
            $attemptQuery->whereIn(
                'passcours_user',
                $usersQuery->pluck('id')
            );
        }

        $attemptCount = $attemptQuery->count();

        $passRate = $attemptCount > 0
            ? round(($passCount / $attemptCount) * 100, 2)
            : 0;

        /**
         * Overdue
         */
        $overdueCourses = DB::table('course_online')
            ->where('active', self::ACTIVE)
            ->whereDate('end_date', '<', now());

        $this->applyCourseFilter($overdueCourses, $filters);

        $overdueCount = $overdueCourses->count();

        /**
         * ต้องสอบซ่อม
         *
         * ตรงนี้ใช้ score_status = retry
         * ถ้าฐานข้อมูลจริงใช้ค่าอื่น เปลี่ยนตรงนี้ได้
         */
        $retryQuery = DB::table('coursescore')
            ->where('score_status', 'retry');

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

            'pass_rate' => $passRate,

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

        foreach ($lines as $line) {

            /**
             * ถ้าเลือก Department
             * ต้องเช็คว่า Line อยู่ใต้ Department หรือไม่
             */
            if (!empty($filters['department_id'])) {

                $lineIds = $this->getDescendantOrgIds(
                    $filters['department_id']
                );

                if (!in_array($line->id, $lineIds)) {
                    continue;
                }
            }

            if (!empty($filters['section_id'])) {

                $sectionIds = $this->getDescendantOrgIds(
                    $filters['section_id']
                );

                if (!in_array($line->id, $sectionIds)) {
                    continue;
                }
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

        foreach ($sections as $section) {

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
                'pass_rate' => $rate,
            ]);
        }

        return $result;
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
            ->selectRaw(
                'COUNT(DISTINCT passcours.passcours_user) as failed_count'
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
         * ตรงนี้ต้องปรับตาม field วันที่เริ่มงานจริงของระบบ
         *
         * สมมติ users.start_date
         */
        $query = DB::table('users')
            ->where('status', '1')
            ->whereNotNull('start_date')
            ->where(
                'start_date',
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
                $user->start_date
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

            $result->push([
                'department_id' => $department->id,
                'department' => $department->title,

                'employees' => count($userIds),

                'completion_rate' => $completion,

                'pass_rate' => $passRate,

                'overdue' => $overdue,

                'retry' => 0,

                'skill_gap' => 0,

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

        for ($i = 5; $i >= 0; $i--) {

            $date = now()
                ->subMonths($i);

            $start = $date
                ->copy()
                ->startOfMonth();

            $end = $date
                ->copy()
                ->endOfMonth();

            $courseIds = $this->getCourseIds($filters);

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

    private function getDescendantOrgIds(
        $parentId
    ): array {
        $result = [(int) $parentId];

        $children = Orgchart::where(
                'parent_id',
                $parentId
            )
            ->where(
                'active',
                self::ACTIVE
            )
            ->pluck('id')
            ->toArray();

        foreach ($children as $childId) {

            $result = array_merge(
                $result,
                $this->getDescendantOrgIds(
                    $childId
                )
            );
        }

        return $result;
    }
}