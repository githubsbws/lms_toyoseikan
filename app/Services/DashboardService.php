<?php

namespace App\Services;

use App\Models\Roadmap;
use App\Models\Users;
use App\Models\Orgchart;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private function getEmployeePosition($user)
    {
        $position = null;
        $team = null;
        $line = null;
        $department = null;

        $org = Orgchart::find($user->org_id);

        while ($org) {

            switch ((int)$org->level) {

                case 6:
                    $position = $org->title;
                    break;

                case 5:
                    $line = $org->title;
                    break;

                case 4:
                    $team = $org->title;
                    break;

                case 3:
                    $department = $org->title;
                    break;
            }

            if (!$org->parent_id) {
                break;
            }

            $org = Orgchart::find((int)$org->parent_id);
        }


        // team จาก table team
        if ($user->team_id) {

            $teamData = Team::find($user->team_id);

            if ($teamData) {
                $team = $teamData->name;
            }
        }


        return [
                    'position' => $position,
                    'team' => $team,
                    'line' => $line,
                    'department' => $department,
                ];
    }

    public function getEmployeeDashboard($user)
    {
        $name = optional($user->profile)->fullname ?? $user->username;


        $employeePosition = $this->getEmployeePosition($user);
        

        $serviceAge = '-';

        if ($user->work_start) {

            $serviceAge = Carbon::parse($user->work_start)
                ->diff(Carbon::now())
                ->format('%m เดือน %d วัน');

        }
        $isNewEmployee = $user->team_id === Users::TEAM_NEWEMP;

        if ($user->team_id === Users::TEAM_NEWEMP) {

            $roadmapQuery = Roadmap::where(
                'line_id',
                $user->Orgchart?->line?->id
            );

        } else {

            $roadmapQuery = Roadmap::where(
                'department_org_id',
                $user->department_org_id
            );
        }
        

        $roadmap = $roadmapQuery
                ->where('active','y')
                ->with([
                    'courses' => function($q) {
                        $q->where('course_online.active','y')
                        ->withPivot([
                            'milestone_days',
                            'order'
                        ]);
                    },
                    'courses.category',
                    'courses.lesson' => function($q) {

                        $q->where('active','y');

                    },
                    'courses.lesson.learn' => function($q) use ($user){

                        $q->where('user_id',$user->id)
                        ->where('pass_year',now()->year);

                    },
                    'courses.groupTesting.questions',
                    'courses.courseScore' => function($q) use ($user){
                        $q->where('user_id',$user->id)
                        ->where('active','y')
                        ->where('pass_year',now()->year);
                    }
                ])
                ->first();
            

        if (!$roadmap) {
            return [
                'totalCourse' => 0,
                'completed' => 0,
                'failed' => 0,
                'inProgress' => 0,
                'notStarted' => 0,
                'courses' => collect(),
            ];
        }

        $courses = $roadmap->courses;


        $probationPeriod = null;

        if ($user->work_start && $isNewEmployee) {

            $startDate = Carbon::parse($user->work_start);

            $currentDay = $startDate->diffInDays(now());


            $milestones = $courses
                ->pluck('pivot.milestone_days')
                ->unique()
                ->sort()
                ->values();


            $currentMilestone = $milestones
                    ->first(function($day) use ($currentDay){

                        return $currentDay <= $day;

                    });


                if (!$currentMilestone) {

                    $currentMilestone = $milestones->last();

                }


            if ($currentMilestone) {

                $previousMilestone = $milestones
                    ->filter(fn($day) => $day < $currentMilestone)
                    ->last();


                $probationPeriod = [
                    'day' => $currentMilestone,

                    'start' => ($previousMilestone
                            ? $startDate->copy()->addDays($previousMilestone)
                            : $startDate
                        )
                        ->locale('th')
                        ->translatedFormat('d M y'),


                    'end' => $startDate
                        ->copy()
                        ->addDays($currentMilestone - 1)
                        ->locale('th')
                        ->translatedFormat('d M y'),
                ];
            }
        }

        $learningPlan = $courses
            ->groupBy('cate_id')
            ->map(function ($group) use ($user) {

                $total = $group->count();

                $totalPercent = $group->sum(function($course) use ($user){
                    return $this->calculateCourseProgress($course,$user);
                });

                $percent = $total > 0
                    ? round($totalPercent / $total)
                    : 0;


                $passed = DB::table('coursescore')
                    ->where('user_id', $user->id)
                    ->where('score_status', 'pass')
                    ->whereIn('course_id', $group->pluck('course_id'))
                    ->count();

                

                // สีของ Progress Bar
                if ($percent >= 80) {
                    $barColor = '#2ecc71';
                } elseif ($percent >= 40) {
                    $barColor = '#f39c12';
                } else {
                    $barColor = '#dcdfe6';
                }

                return [
                    'title' => optional($group->first()->category)->cate_title
                                ?? 'หมวด '.$group->first()->cate_id,

                    'total' => $total,
                    'passed' => $passed,
                    'percent' => $percent,
                    'color' => $barColor,
                ];
            })
            ->values();

        $completed = 0;
        $failed = 0;
        $inProgress = 0;
        $notStarted = 0;

        foreach ($courses as $course) {

            $progress = $this->calculateCourseProgress($course,$user);


            $score = $course->courseScore
                ->sortByDesc('attempt')
                ->first();


            // ผ่านแล้ว
            if ($score && $score->score_status == 'pass') {

                $completed++;
                continue;

            }


            // สอบตก
            if ($score && $score->score_status == 'fail') {

                $failed++;
                continue;

            }


            // เริ่มเรียนแล้ว
            if ($progress > 0) {

                $inProgress++;

            } else {

                $notStarted++;

            }

        }

        $totalProgress = $courses->sum(function($course)  use ($user){

            return $this->calculateCourseProgress($course,$user);

        });

        $totalCourse = $courses->count();

        $completedPercent = $totalCourse > 0
            ? round(($completed / $totalCourse) * 100)
            : 0;


        $inProgressPercent = $totalCourse > 0
            ? round(($inProgress / $totalCourse) * 100)
            : 0;


        $notStartedPercent = $totalCourse > 0
            ? round(($notStarted / $totalCourse) * 100)
            : 0;


        $failedPercent = $totalCourse > 0
            ? round(($failed / $totalCourse) * 100)
            : 0;
        
        $overallProgress = $totalCourse > 0
            ? round($totalProgress / $totalCourse)
            : 0;
        $progressByCategory = $courses
            ->groupBy('cate_id')
            ->map(function ($group) use ($user) {

            $total = $group->count();

            $percent = $group->sum(function($course) use ($user){
                return $this->calculateCourseProgress($course,$user);
            });


            $percent = $total > 0
                ? round($percent / $total)
                : 0;

            if ($percent == 100) {
                $status = 'ผ่าน';
                $color = 'green';
            } elseif ($percent == 0) {
                $status = 'ยังไม่เริ่ม';
                $color = 'grey';
            } else {
                $status = 'กำลังเรียน';
                $color = 'orange';
            }

            return [
                'name' => optional($group->first()->category)->cate_title ?? 'หมวด '.$group->first()->cate_id,
                'percent' => $percent,
                'status' => $status,
                'color' => $color,
            ];
        })->values();
        $learningProgressPercent = 0;

        if($inProgress > 0){

            $learningProgressPercent = $courses
                ->filter(function($course) use ($user){

                    $score = $course->courseScore
                        ->sortByDesc('attempt')
                        ->first();

                    if($score?->score_status == 'pass'){
                        return false;
                    }

                    return $this->calculateCourseProgress($course,$user) > 0;

                })
                ->avg(function($course) use ($user){

                    return $this->calculateCourseProgress($course,$user);

                });


            $learningProgressPercent = round($learningProgressPercent);
        }

        $deadlineCourses = collect();

        foreach ($courses as $course) {

            $pass = $course->courseScore
                    ->where('score_status','pass')
                    ->isNotEmpty();

            if ($pass) {
                continue;
            }

            $days = $course->pivot->milestone_days ?? 0;

            $deadline = null;

            if ($user->work_start) {
                $deadline = Carbon::parse($user->work_start)
                    ->addDays($days);
            }

            $deadlineCourses->push([
                'course_id' => $course->course_id,
                'course_title' => $course->course_title,
                'deadline' => $deadline,
                'days' => $days,
            ]);
        }

        $deadlineCourses = $deadlineCourses
            ->sortBy('deadline')
            ->take(5);

        $continueCourses = collect();

            foreach ($courses as $course) {

                $pass = $course->courseScore
                    ->where('score_status','pass')
                    ->isNotEmpty();

                if ($pass) {
                    continue;
                }

                // จำนวน Lesson ทั้งหมด
                $totalLesson = $course->lesson->count();

                // จำนวน Lesson ที่เรียนแล้ว
                $learnLesson = $course->lesson
                        ->filter(function($lesson) use ($user){

                            return $lesson->learn
                                ->where('user_id',$user->id)
                                ->where('lesson_status','pass')
                                ->isNotEmpty();

                        })
                        ->count();

                $percent = $this->calculateCourseProgress($course,$user);

                $continueCourses->push([
                    'course_id' => $course->course_id,
                    'course_title' => $course->course_title,
                    'course_short_title' => $course->course_short_title,
                    'percent' => $percent,
                    'learnLesson' => $learnLesson,
                    'totalLesson' => $totalLesson,
                ]);
            }

            $continueCourses = $continueCourses
                ->sortByDesc('percent')
                ->values();

        $courseIds = $courses->pluck('course_id');


        $failCourses = DB::table('coursescore as cs')
            ->join('course_online as c', 'c.course_id', '=', 'cs.course_id')
            ->where('cs.user_id', $user->id)
            ->where('cs.active', 'y')
            ->where('cs.score_status', 'fail')
            ->whereIn('cs.course_id',$courseIds)
            ->select(
                'cs.course_id',
                'c.course_title',
                'c.course_short_title',
                'cs.score_number',
                'cs.score_total'
            )
            ->orderByDesc('cs.update_date')
            ->get()
            ->map(function ($item) {

                $percent = 0;

                if ($item->score_total > 0) {
                    $percent = round(($item->score_number / $item->score_total) * 100);
                }

                return [
                    'course_id' => $item->course_id,
                    'course_title' => $item->course_title,
                    'course_short_title' => strip_tags($item->course_short_title),
                    'score' => $item->score_number,
                    'total' => $item->score_total,
                    'percent' => $percent,
                ];
            });
        
        $latestAssessments = DB::table('score_assessment as sa')
            ->join(
                'passcours as pc',
                'pc.passcours_id',
                '=',
                'sa.passcours_id'
            )
            ->join(
                'course_score_weight as csw',
                'csw.id',
                '=',
                'sa.id_course_score_weight'
            )
            ->join(
                'course_online as c',
                'c.course_id',
                '=',
                'csw.course_id'
            )
            ->where('pc.passcours_user',$user->id)
            ->where('sa.active','y')
            ->select(
                'c.course_title',
                'c.course_short_title',
                'sa.score',
                'pc.passcours_status',
                'pc.passcours_date'
            )
            ->orderByDesc('pc.passcours_date')
            ->limit(5)
            ->get()
            ->map(function($item){

                return [
                    'title' => $item->course_title,
                    'short_title' => strip_tags($item->course_short_title),

                    'score' => $item->score,

                    'pass' => $item->passcours_status == 'pass',

                    'date' => Carbon::parse($item->passcours_date)
                        ->format('d/m/Y')
                ];

            });

        $learningHistory = $courses
            ->map(function($course) use ($user){

                // วันที่เรียนล่าสุดของ course นี้
                $lastLearn = $course->lesson
                    ->flatMap(function($lesson){
                        return $lesson->learn;
                    })
                    ->where('user_id',$user->id)
                    ->sortByDesc('learn_date')
                    ->first();


                if(!$lastLearn){
                    return null;
                }


                $percent = $this->calculateCourseProgress(
                    $course,
                    $user
                );


                $score = $course->courseScore
                    ->where('user_id',$user->id)
                    ->sortByDesc('attempt')
                    ->first();


                return [
                    'course_id'=>$course->course_id,

                    'course_title'=>$course->course_title,

                    'status'=> $score?->score_status === 'pass'
                        ? 'เรียนจบ'
                        : 'กำลังเรียน',

                    'date'=>Carbon::parse($lastLearn->learn_date)
                        ->locale('th')
                        ->translatedFormat('d M y'),

                    'percent'=>$percent,

                    'icon'=> $score?->score_status === 'pass'
                        ? 'fa-circle-check'
                        : 'fa-book-open',

                    'color'=> $score?->score_status === 'pass'
                        ? '#63e672'
                        : '#f39c12',
                ];

            })
            ->filter()
            ->sortByDesc('date')
            ->take(5)
            ->values();

        $newEmployeeTimeline = $courses
            ->groupBy(function($course){

                return $course->pivot->milestone_days;

            })
            ->map(function($group,$day) use ($user){

                $total = $group->count();

                $passed = $group->filter(function($course) use ($user){

                        return $this->calculateCourseProgress($course,$user) >= 100;

                    })->count();


                return [

                    'day'=>$day,

                    'total'=>$total,

                    'passed'=>$passed,

                    'percent'=>$total > 0
                        ? round(($passed/$total)*100)
                        :0

                ];


            })
            ->values();


        $currentMilestoneCourses = collect();

        if ($probationPeriod) {

            $currentMilestoneCourses = $courses
                ->filter(function($course) use ($probationPeriod, $user){

                    return $course->pivot->milestone_days 
                        == $probationPeriod['day']
                        &&
                        !$course->courseScore
                            ->where('score_status','pass')
                            ->isNotEmpty();

                })
                ->map(function($course){

                    return [
                        'course_id' => $course->course_id,
                        'title' => $course->course_title,
                        'short_title' => $course->course_short_title,
                        'milestone_days' => $course->pivot->milestone_days,
                    ];

                })
                ->values();
        }

        return [
                    'employee' => [
                        'user' => $user,
                        'name' => $name,
                        'position' => $employeePosition,
                        'serviceAge' => $serviceAge,
                    ],
                    'totalCourse' => $totalCourse,

                    'completed' => $completed,
                    'completedPercent' => $completedPercent,

                    'inProgress' => $inProgress,
                    'inProgressPercent' => $inProgressPercent,

                    'notStarted' => $notStarted,
                    'notStartedPercent' => $notStartedPercent,

                    'failed' => $failed,
                    'failedPercent' => $failedPercent,
                    
                    'progressOffset' => 282.7 - (($overallProgress / 100) * 282.7),
                    'progressByCategory' => $progressByCategory,
                    'learningProgressPercent'=>$learningProgressPercent,
                    'overallProgress'=>$overallProgress,

                    'courses' => $courses,
                    'learningPlan'=>$isNewEmployee
                            ? collect()
                            : $learningPlan,
                    'deadlineCourses' => $deadlineCourses,
                    'continueCourses' => $continueCourses,
                    'continueCount' => $continueCourses->count(),
                    'failCourses' => $failCourses,
                    'failCount' => $failCourses->count(),

                    'latestAssessments' => $latestAssessments,

                    'learningHistory'=>$learningHistory,

                    'isNewEmployee' => $isNewEmployee,
                    'newEmployeeTimeline'=>$isNewEmployee
                            ? $newEmployeeTimeline
                            : collect(),
                    'probationPeriod' => $probationPeriod,
                    'probationCourses' => $currentMilestoneCourses,
                ];
    }

    private function calculateCourseProgress($course,$user)
    {
        $totalLessons = $course->lesson->count();


        $passedLessons = $course->lesson
            ->filter(function($lesson) use ($user){

                return $lesson->learn
                    ->where('user_id',$user->id)
                    ->where('lesson_status','pass')
                    ->isNotEmpty();

            })
            ->count();


        $hasQuestions = $course->groupTesting?->questions?->isNotEmpty() ?? false;


        $examPassed = $course->courseScore
            ->where('score_status','pass')
            ->isNotEmpty()
            ? 1
            : 0;


        $totalSteps = $totalLessons + ($hasQuestions ? 1 : 0);

        if($totalSteps == 0){
            return 0;
        }
        

       return round(
            (($passedLessons + $examPassed) / $totalSteps) * 100
        );

        
    }
}