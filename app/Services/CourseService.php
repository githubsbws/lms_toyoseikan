<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Orgcourse;
use App\Models\Users;

class CourseService
{
    // app/Services/CourseService.php
    public function getCoursesForUser(Users $user)
    {
        // 1. Base Query พร้อม Eager Loading ที่สะอาดขึ้น
        $query = Course::with([
            'lesson' => function($q) {
                $q->where('active', 'y')
                ->with([
                    'file' => fn($q) => $q->where('active', 'y'),
                    'filedoc' => fn($q) => $q->where('active', 'y'),
                ]);
            }
        ])
        ->where('course_online.active', 'y');

        // 2. Branching Logic
        if ($user->team_id === Users::TEAM_NEWEMP) {
            return $this->applyRoadmapCriteria($query, $user);
        }

        return $this->applyOrgCriteria($query, $user);
    }

    private function applyRoadmapCriteria($query, $user)
    {
        $lineId = $user->Orgchart->line->id ?? null;

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
