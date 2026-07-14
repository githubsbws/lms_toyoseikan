<?php

namespace App\Services;

use App\Models\Users;
use App\Models\Roadmap;
use App\Models\RoadmapCourse;
use App\Models\PassCourse;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Learn;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ManagertDashboardService
{
    public function getRoadmapMonthly(Users $loginUser)
    {

        $users = $this->getTeamUsers($loginUser);

        $roadmapMap = $this->buildRoadmapMap();

        $roadmapCourseMap = $this->buildRoadmapCourseMap();

        $passCourseMap = $this->buildPassCourseMap($users);

        return $this->calculateMonthly(
            $users,
            $roadmapMap,
            $roadmapCourseMap,
            $passCourseMap
        );

            // dd($roadmapCourseMap);

    }

    private function getTeamUsers($loginUser)
    {
        return Users::with([
            'orgchart.line'
        ])
        ->where('team_id', $loginUser->team_id)
        ->where('status', '1')
        ->get();
    }

    private function getRoadmaps()
    {
        return Roadmap::where('active', 'y')->get();
    }

    private function getRoadmapCourses()
    {
        return RoadmapCourse::where('active', 'y')->get();
    }

    private function getPassCourses($users)
    {
        return PassCourse::whereIn('passcours_user', $users->pluck('id'))
            ->where('passcours_status', 'pass')
            ->whereBetween('passcours_date', [
                Carbon::now()->subMonths(5)->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->get();
    }

    private function buildRoadmapMap()
    {
        $roadmaps = Roadmap::where('active', 'y')->get();

        $map = [];

        foreach ($roadmaps as $roadmap) {

            $map
                [$roadmap->org_id]
                [$roadmap->department_org_id]
                [$roadmap->line_id] = $roadmap->id;

        }

        return $map;
    }

    private function buildRoadmapCourseMap()
    {
        $courses = RoadmapCourse::where('active', 'y')
            ->select('roadmap_id', 'course_id')
            ->get();

        $map = [];

        foreach ($courses as $course) {

            $map[$course->roadmap_id][$course->course_id] = true;

        }

        return $map;
    }

    private function buildPassCourseMap($users)
    {
        $passCourses = PassCourse::whereIn(
                'passcours_user',
                $users->pluck('id')
            )
            ->where('passcours_status', 'pass')
            ->select(
                'passcours_user',
                'passcours_cours',
                'passcours_date'
            )
            ->orderBy('passcours_date')
            ->get();

        $map = [];

        foreach ($passCourses as $pass) {

            if (!isset($map[$pass->passcours_user][$pass->passcours_cours])) {

                $map[$pass->passcours_user][$pass->passcours_cours]
                    = $pass->passcours_date;

            }

        }

        return $map;
    }

    private function calculateMonthly(
        $users,
        $roadmapMap,
        $roadmapCourseMap,
        $passCourseMap
    )
    {
        $result = [];

        $totalUser = $users->count();

        for ($i = 5; $i >= 0; $i--) {

            $month = now()->subMonths($i)->endOfMonth();

            $pass = 0;

            foreach ($users as $user) {

                $lineId = optional(optional($user->orgchart)->line)->id;

                $roadmapId = $roadmapMap
                    [$user->org_id]
                    [$user->department_org_id]
                    [$lineId] ?? null;

                if (!$roadmapId) {
                    continue;
                }

                $roadmapCourses = $roadmapCourseMap[$roadmapId] ?? [];

                if (empty($roadmapCourses)) {
                    continue;
                }

                $isPass = true;

                foreach ($roadmapCourses as $courseId => $dummy) {

                    if (!isset($passCourseMap[$user->id][$courseId])) {

                        $isPass = false;
                        break;

                    }

                    $passDate = \Carbon\Carbon::parse(
                        $passCourseMap[$user->id][$courseId]
                    );

                    if ($passDate->gt($month)) {

                        $isPass = false;
                        break;

                    }

                }

                if ($isPass) {

                    $pass++;

                }

            }

            $thaiMonths = [
                            1 => 'ม.ค.',
                            2 => 'ก.พ.',
                            3 => 'มี.ค.',
                            4 => 'เม.ย.',
                            5 => 'พ.ค.',
                            6 => 'มิ.ย.',
                            7 => 'ก.ค.',
                            8 => 'ส.ค.',
                            9 => 'ก.ย.',
                            10 => 'ต.ค.',
                            11 => 'พ.ย.',
                            12 => 'ธ.ค.',
                        ];

            $result[] = [
                'month' => $thaiMonths[$month->month] . ' ' . ($month->year + 543),
                'pass' => $pass,
                'percent' => $totalUser > 0
                    ? round(($pass / $totalUser) * 100, 2)
                    : 0,
            ];

        }

        return $result;
    }

    public function getMandatorySummary($loginUser)
    {
        $users = $this->getTeamUsers($loginUser);

        $roadmapMap = $this->buildRoadmapMap();

        $mandatoryCourseMap = $this->buildMandatoryCourseMap();

        $passCourseMap = $this->buildPassCourseMap($users);

        return $this->calculateMandatorySummary(
            $users,
            $roadmapMap,
            $mandatoryCourseMap,
            $passCourseMap
        );
    }

    private function buildMandatoryCourseMap()
    {
        $courses = RoadmapCourse::where('active', 'y')
            ->where('milestone_days', '<>', 99)
            ->select('roadmap_id', 'course_id')
            ->get();

        $map = [];

        foreach ($courses as $course) {

            $map[$course->roadmap_id][$course->course_id] = true;

        }

        return $map;
    }

    private function calculateMandatorySummary(
        $users,
        $roadmapMap,
        $mandatoryCourseMap,
        $passCourseMap
    )
    {
        $complete = 0;
        $warning = 0;
        $notComplete = 0;

        foreach ($users as $user) {

            $lineId = optional(optional($user->orgchart)->line)->id;

            $roadmapId = $roadmapMap
                [$user->org_id]
                [$user->department_org_id]
                [$lineId] ?? null;

            if (!$roadmapId) {
                continue;
            }

            $courses = $mandatoryCourseMap[$roadmapId] ?? [];

            if (empty($courses)) {
                continue;
            }

            $totalCourse = count($courses);
            $passCourse = 0;

            foreach ($courses as $courseId => $course) {

                if (isset($passCourseMap[$user->id][$courseId])) {
                    $passCourse++;
                }

            }

            if ($passCourse == 0) {

                // ยังไม่เริ่มเรียนเลย
                $notComplete++;

            } elseif ($passCourse == $totalCourse) {

                // ผ่านครบทุกวิชา
                $complete++;

            } else {

                // ผ่านบางวิชา แต่ยังไม่ครบ
                $warning++;

            }
        }

        $total = $complete + $warning + $notComplete;

        return [

            'complete' => $complete,

            'warning' => $warning,

            'not_complete' => $notComplete,

            'complete_percent' => $total
                ? round(($complete / $total) * 100)
                : 0,

            'warning_percent' => $total
                ? round(($warning / $total) * 100)
                : 0,

            'not_complete_percent' => $total
                ? round(($notComplete / $total) * 100)
                : 0,

            'total_user' => $total,

        ];
    }

    public function getNearExpireCourses($loginUser)
{
    $users = $this->getTeamUsers($loginUser);

    $roadmapMap = $this->buildRoadmapMap();

    $mandatoryCourseMap = $this->buildMandatoryCourseMap();

    $passCourseMap = $this->buildPassCourseMap($users);

    return $this->calculateNearExpireCourses(
        $users,
        $roadmapMap,
        $mandatoryCourseMap,
        $passCourseMap
    );
}

private function calculateNearExpireCourses(
    $users,
    $roadmapMap,
    $mandatoryCourseMap,
    $passCourseMap
)
{
    $courseUsers = [];

    foreach ($users as $user) {

        $lineId = optional(optional($user->orgchart)->line)->id;

        $roadmapId = $roadmapMap
            [$user->org_id]
            [$user->department_org_id]
            [$lineId] ?? null;

        if (!$roadmapId) {
            continue;
        }

        $courses = $mandatoryCourseMap[$roadmapId] ?? [];

        foreach ($courses as $courseId => $dummy) {

            $courseUsers[$courseId][] = $user->id;
        }
    }

    if (empty($courseUsers)) {
        return collect();
    }

    $courseOnline = Course::whereIn('course_id', array_keys($courseUsers))
        ->whereDate('end_date', '>=', now())
        ->select('course_id', 'course_title', 'end_date')
        ->get()
        ->keyBy('course_id');

    $result = collect();

    foreach ($courseUsers as $courseId => $userIds) {

        if (!isset($courseOnline[$courseId])) {
            continue;
        }

        $unfinished = 0;

        foreach ($userIds as $userId) {

            if (!isset($passCourseMap[$userId][$courseId])) {
                $unfinished++;
            }
        }

        if ($unfinished == 0) {
            continue;
        }

        $result->push([
            'course_id'   => $courseId,
            'course_name' => $courseOnline[$courseId]->course_title,
            'end_date'    => $courseOnline[$courseId]->end_date,
            'unfinished'  => $unfinished,
        ]);
    }

    return $result
        ->sortBy('end_date')
        ->take(3)
        ->values();
}

public function getTeamLearningProgress($loginUser, $keyword = null)
{
    // คนในทีม
    $users = $this->getTeamUsers($loginUser);

    // ค้นหาชื่อ-นามสกุล
    if (!empty($keyword)) {

        $keyword = trim($keyword);

        $users = $users->filter(function ($user) use ($keyword) {

            $fullname = trim(
                ($user->profiles->firstname ?? '') . ' ' .
                ($user->profiles->lastname ?? '')
            );

            return stripos($fullname, $keyword) !== false;

        })->values();
    }

    // Roadmap
    $roadmapMap = $this->buildRoadmapMap();

    // ทุกวิชาใน Roadmap
    $roadmapCourseMap = $this->buildRoadmapCourseMapAll();

    // รวม Course ทั้งหมด
    $courseIds = collect($roadmapCourseMap)
        ->flatMap(function ($courses) {
            return array_keys($courses);
        })
        ->unique()
        ->values();

    // Lesson
    $lessonMap = $this->buildLessonMap($courseIds);

    // Part 2
    $learnMap = $this->buildLearnMap($users->pluck('id'));

    $passCourseDateMap = $this->buildPassCourseDateMap($users->pluck('id'));

    $result = $this->calculateTeamLearningProgress(
        $users,
        $roadmapMap,
        $roadmapCourseMap,
        $lessonMap,
        $learnMap,
        $passCourseDateMap
    );

    return $this->paginate($result, 10);
}

private function buildRoadmapCourseMapAll()
{
    $courses = RoadmapCourse::where('active', 'y')
        ->select(
            'roadmap_id',
            'course_id'
        )
        ->get();

    $map = [];

    foreach ($courses as $course) {

        $map[$course->roadmap_id][$course->course_id] = true;

    }

    return $map;
}

private function buildLessonMap($courseIds)
{
    $lessons = Lesson::whereIn('course_id', $courseIds)
        ->where('active', 'y')
        ->select(
            'id',
            'course_id'
        )
        ->get();

    $map = [];

    foreach ($lessons as $lesson) {

        $map[$lesson->course_id][$lesson->id] = true;

    }

    return $map;
}

private function buildLearnMap($userIds)
{
    $learns = Learn::whereIn('user_id', $userIds)
        ->where('lesson_status', 'pass')
        ->select(
            'user_id',
            'course_id',
            'lesson_id'
        )
        ->get();

    $map = [];

    foreach ($learns as $learn) {

        $map[$learn->user_id]
            [$learn->course_id]
            [$learn->lesson_id] = true;

    }

    return $map;
}

private function buildPassCourseDateMap($userIds)
{
    $passes = PassCourse::whereIn('passcours_user', $userIds)
        ->where('passcours_status', 'pass')
        ->select(
            'passcours_user',
            'passcours_cours',
            'passcours_date'
        )
        ->get();

    $map = [];

    foreach ($passes as $pass) {

        $map[$pass->passcours_user]
            [$pass->passcours_cours] = $pass->passcours_date;

    }

    return $map;
}

private function calculateTeamLearningProgress(
    $users,
    $roadmapMap,
    $roadmapCourseMap,
    $lessonMap,
    $learnMap,
    $passCourseDateMap
)
{
    // ดึงข้อมูลหลักสูตรทีเดียว
    $courseOnline = Course::whereIn(
        'course_id',
        collect($roadmapCourseMap)
            ->flatMap(fn($item) => array_keys($item))
            ->unique()
    )
    ->select(
        'course_id',
        'course_title as course_name',
        'end_date'
    )
    ->get()
    ->keyBy('course_id');

    $rows = collect();

    foreach ($users as $user) {

        $lineId = optional(optional($user->orgchart)->line)->id;

        $roadmapId = $roadmapMap
            [$user->org_id]
            [$user->department_org_id]
            [$lineId] ?? null;

        if (!$roadmapId) {
            continue;
        }

        $courses = $roadmapCourseMap[$roadmapId] ?? [];

        foreach ($courses as $courseId => $dummy) {

            $totalLesson = isset($lessonMap[$courseId])
                ? count($lessonMap[$courseId])
                : 0;

            if ($totalLesson == 0) {
                continue;
            }

            $passLesson = 0;

            if (isset($learnMap[$user->id][$courseId])) {

                $passLesson = count(
                    array_intersect_key(
                        $lessonMap[$courseId],
                        $learnMap[$user->id][$courseId]
                    )
                );

            }

            $progress = round(
                ($passLesson / $totalLesson) * 100
            );

            $status = $progress == 100
                ? 'เรียนครบ'
                : 'เรียนอยู่';

            $displayDate = $progress == 100
                ? ($passCourseDateMap[$user->id][$courseId] ?? null)
                : ($courseOnline[$courseId]->end_date ?? null);

            $rows->push([

                'user_id' => $user->id,

                'fullname' => trim(
                    ($user->profiles->firstname ?? '') .
                    ' ' .
                    ($user->profiles->lastname ?? '')
                ),

                'position' => $user->orgchart->title ?? '-',

                'course_id' => $courseId,

                'course_name' => $courseOnline[$courseId]->course_name ?? '-',

                'progress' => $progress,

                'pass_lesson' => $passLesson,

                'total_lesson' => $totalLesson,

                'status' => $status,

                'display_date' => $displayDate,

            ]);
        }
    }

    return $rows;
}

private function paginate($items, $perPage = 10)
{
    $page = Paginator::resolveCurrentPage() ?: 1;

    $items = collect($items);

    return new LengthAwarePaginator(
        $items->forPage($page, $perPage)->values(),
        $items->count(),
        $perPage,
        $page,
        [
            'path' => Paginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]
    );
}

public function getTeamLatestActivity($loginUser)
{
    $teamUsers = $this->getTeamUsers($loginUser);

    $userIds = $teamUsers->pluck('id');

    return PassCourse::query()
        ->join('users as u', 'passcours.passcours_user', '=', 'u.id')
        ->leftJoin('profiles as p', 'u.id', '=', 'p.user_id')
        ->leftJoin('course_online as c', 'passcours.passcours_cours', '=', 'c.course_id')
        ->whereIn('passcours.passcours_user', $userIds)
        ->where('passcours.passcours_status', 'pass')
        ->select([
            'passcours.passcours_date',
            'passcours.passcours_user',
            'c.course_title as course_name',
            'p.firstname',
            'p.lastname',
            'u.pic_user',
        ])
        ->orderByDesc('passcours.passcours_date')
        ->limit(4)
        ->get()
        ->map(function ($row) {

            return [

                'fullname' => trim($row->firstname.' '.$row->lastname),

                'course_name' => $row->course_name,

                'date' => optional($row->passcours_date)->format('Y-m-d'),

                'time' => optional($row->passcours_date)->format('H:i'),

                'pic_user' => $row->pic_user,

            ];

        });
}


}