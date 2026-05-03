<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Orgcourse;
use App\Models\Users;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    const STATUS_ACTIVE = 'y';
    // app/Services/CourseService.php
    public function getCoursesForUser(Users $user)
    {
        // 1. Base Query พร้อม Eager Loading ที่สะอาดขึ้น
        $query = Course::with([
            'lesson' => function($q) use ($user) {
                $q->where('active', 'y')
                ->with([
                    'file' => fn($q) => $q->where('active', self::STATUS_ACTIVE)
                        ->with([
                            // ← eager load learn_file ของ user นี้
                            'learnFile' => fn($q) => $q->whereHas('learn',
                                fn($q) => $q->where('user_id', $user->id)
                            )
                        ]),
                    'filedoc' => fn($q) => $q->where('active', self::STATUS_ACTIVE),
                    // ← eager load learn ของ user นี้
                    'learn' => fn($q) => $q->where('user_id', $user->id),
                ]);
            }
        ])
        ->where('course_online.active', self::STATUS_ACTIVE);

        // 2. Branching Logic
        if ($user->team_id === Users::TEAM_NEWEMP) {
            return $this->applyRoadmapCriteria($query, $user);
        }

        return $this->applyOrgCriteria($query, $user);
    }

    private function applyRoadmapCriteria($query, $user) : LengthAwarePaginator|iterable
    {
        $lineId = $user->Orgchart?->line?->id ?? null;

        if (!$lineId) return collect(); // Defensive: ไม่มี Line ID ไม่ให้เห็นคอร์ส

        return $query->join('roadmap_course', 'course_online.course_id', '=', 'roadmap_course.course_id')
            ->join('roadmap', 'roadmap_course.roadmap_id', '=', 'roadmap.id')
            ->where('roadmap.line_id', $lineId)
            ->orderBy('roadmap_course.order', 'asc')
            ->select('course_online.*')
            ->paginate(5);
    }

    private function applyOrgCriteria($query, $user)
    {
        $orgCourseIds = Orgcourse::where('orgchart_id', $user->org_id)->pluck('course_id');

        if ($orgCourseIds->isEmpty()) {
            return collect();
        }

        return $query->whereIn('course_online.course_id', $orgCourseIds)->paginate(5);
    }
}
