<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseExamService
{
    const STATUS_ACTIVE = 'y';

    public function getMultipleChoiceExam(int $courseId)
    {
        $userId = Auth::id();
        // 1. Eager Loading ดึงกลุ่มข้อสอบ คำถาม และช้อยส์ทั้งหมดรวดเดียว ดักสถานะ Active
        $course = Course::with([
            'groupTesting' => function ($q) {
                $q->where('active',self::STATUS_ACTIVE)
                ->with([
                    'questions' => fn($q) => $q->where('ques_type',1)
                    ->with(['choices'])
                    ]);
            },
            'courseScore' => function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->where('pass_year', now()->year);
            },
            'lesson' => function($q) use ($userId) {
                $q->where('active', 'y')->with([
                    'learn' => fn($subQ) => $subQ->where('user_id', $userId)->where('pass_year', now()->year)
                ]);
            }
        ])->findOrFail($courseId);

        // 2. คำนวณเงื่อนไขความปลอดภัย (Guard Clauses)
        $totalLessons  = $course->lesson->count();
        $passedLessons = $course->lesson->filter(fn($l) => $l->learn->first()?->lesson_status === 'pass')->count();

        $userScores     = $course->courseScore;
        $hasPassed      = $userScores->where('score_status', 'pass')->isNotEmpty();
        $attemptedCount = $userScores->count();
        $maxAttempts    = 1 + (int)($course->course_retest_amount ?? 0);
        $hasQuestions   = $course->groupTesting?->questions?->isNotEmpty() ?? false;

        // 3. ตรวจสอบสิทธิ์แบบเด็ดขาด หากไม่ผ่านเงื่อนไขให้โยน Exception ออกไป
        if (!$hasQuestions) {
            throw new \Exception('หลักสูตรนี้ยังไม่มีการจัดเตรียมข้อสอบปรนัย');
        }
        if ($totalLessons === 0 || $passedLessons < $totalLessons) {
            throw new \Exception('คุณต้องเรียนให้ครบทุกบทเรียนก่อนเข้าสอบ');
        }
        if ($hasPassed) {
            throw new \Exception('คุณสอบผ่านหลักสูตรนี้ไปแล้ว ไม่ต้องสอบซ้ำ');
        }
        if ($attemptedCount >= $maxAttempts) {
            throw new \Exception('คุณใช้สิทธิ์สอบซ่อมครบกำหนดแล้ว กรุณาติดต่อแอดมิน');
        }

        return $course;
    }

    public function getEssayExam(int $courseId)
    {
        $userId = Auth::id();

        $course = Course::with([
            'groupTesting' => function ($q) {
                $q->where('active',self::STATUS_ACTIVE)
                ->with([
                    'questions' => fn($q) => $q->where('ques_type',3)
                    ->with(['images'])
                    ]);
            },
            'courseScore' => function ($q) use ($userId) {
                $q->where('user_id', $userId)
                ->whereYear('created_at', now()->year);
            },
            'lesson' => function($q) use ($userId) {
                $q->where('active', 'y')->with([
                    'learn' => fn($subQ) => $subQ->where('user_id', $userId)->where('pass_year', now()->year)
                ]);
            }
        ])->findOrFail($courseId);

        // --- ก๊อปปี้ Logic Guard Clauses เดิมมาดักความปลอดภัยตรงนี้ ---
        $totalLessons  = $course->lesson->count();
        $passedLessons = $course->lesson->filter(fn($l) => $l->learn->first()?->lesson_status === 'pass')->count();
        $userScores     = $course->courseScore;
        $hasPassed      = $userScores->where('score_status', 'pass')->isNotEmpty();
        $attemptedCount = $userScores->count();
        $maxAttempts    = 1 + (int)($course->course_retest_amount ?? 0);
        $hasQuestions   = $course->groupTesting?->questions?->isNotEmpty() ?? false;

        if (!$hasQuestions) { throw new \Exception('หลักสูตรนี้ยังไม่มีการจัดเตรียมข้อสอบบรรยาย'); }
        if ($totalLessons === 0 || $passedLessons < $totalLessons) { throw new \Exception('คุณต้องเรียนให้ครบทุกบทเรียนก่อนเข้าสอบ'); }
        if ($hasPassed) { throw new \Exception('คุณสอบผ่านหลักสูตรนี้ไปแล้ว ไม่ต้องสอบซ้ำ'); }
        if ($attemptedCount >= $maxAttempts) { throw new \Exception('คุณใช้สิทธิ์สอบซ่อมครบกำหนดแล้ว กรุณาติดต่อแอดมิน'); }

        return $course;
    }
}
