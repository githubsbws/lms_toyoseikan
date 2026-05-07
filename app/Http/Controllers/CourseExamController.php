<?php

namespace App\Http\Controllers;

use App\Services\CourseExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseExamController extends Controller
{
    public function __construct(
        protected CourseExamService $courseExamService,
    ) {}
    public function multipleExam(int $course_id)
    {
        if(Auth::check()){
            $questions = $this->courseExamService->getMultipleChoiceExam($course_id);
            return view('course.exam.exam-multiple',compact('questions'));
        }
    }

    public function essayExam(int $course_id)
    {
        if(Auth::check()){
            $questions = $this->courseExamService->getEssayExam($course_id);
            return view('course.exam.exam-essay',compact('questions'));
        }
    }
}
