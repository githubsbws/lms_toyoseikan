<?php

namespace App\Http\Controllers;

use App\Enums\LessonStatus;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Score;
use App\Models\Lesson;
use App\Models\Manage;
use App\Models\Question;
use App\Models\FileDoc;
use App\Models\Learn;
use App\Models\LearnFile;
use App\Models\Orgchart;
use App\Models\Orgcourse;
use App\Models\File;
use App\Models\Grouptesting;
use App\Models\Images;
use App\Models\LearnFileDoc;
use App\Models\OrgchartUser;
use App\Models\Roadmap;
use App\Models\RoadmapCourse;
use App\Models\Users;
use App\Services\CourseService;
use App\Services\LessonProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourseController extends Controller
{


    // ฉีด Service เข้ามาทาง Constructor
    public function __construct(
        protected CourseService $courseService,
        protected LessonProgressService $progressService,
    ) {}

    function course(Request $request)
    {
        if(Auth::check()){
            $course_detail = $this->courseService->getCoursesForUser(Auth::user());

            return view("course.course",compact('course_detail'));
        }else{
            return redirect()->route('index');
        }
    }
    // Lession

    public function lessonLearn(int $lessonId, int $fileId,)
    {
        // โหลด Lesson พร้อม Course และไฟล์ที่ระบุ รวมถึงสถานะการเรียนล่าสุด
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $file = File::findOrFail($fileId);

        $this->authorize('view', $lesson);

        // ดึงสถานะการเรียนปัจจุบัน (ถ้ามี)
        $learnFile = LearnFile::whereHas('learn', function($q) use ($lessonId) {
                $q->where('user_id', auth()->id())->where('lesson_id', $lessonId);
            })
            ->where('file_id', $fileId)
            ->first();
        $hasLearnComplete = $learnFile && $learnFile->learn_file_status === LessonStatus::Success->value;
        return view('course.course-lesson', compact('lesson', 'file', 'learnFile','hasLearnComplete'));
    }

    public function streamVideo(int $fileId): BinaryFileResponse
    {
        try{
            $file = File::findOrFail($fileId);

        // Security: เช็คสิทธิ์ก่อนส่งไฟล์
        // $this->authorize('view', $file->lesson);

        $path = public_path('images/uploads/lesson/' . $file->filename);
        if (!file_exists($path)) {
            abort(404);
        }

        // BinaryFileResponse รองรับ HTTP 206 Partial Content อัตโนมัติ
        return response()->file($path, [
            'Content-Type' => 'video/mp4',
            'Cache-Control' => 'private, max-age=3600',
        ]);
        }catch (\Exception $e){
            Log::error("Video Stream Failed: " . $e->getMessage(), [
                'user_id' => auth()->id(),
                'file_id' => $file_id ?? 'N/A',
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                // ไม่ต้องเก็บ Trace ทั้งหมดลง Log ปกติ เพราะมันเปลืองเนื้อที่
                // ยกเว้นจะเป็นเคสที่หาสาเหตุยากจริงๆ
            ]);

            // 2. Return Response (External Visibility)
            // ส่ง HTTP 500 หรือ 400 พร้อมข้อความที่ User อ่านแล้วเข้าใจแต่ไม่รู้ไส้ในระบบ
            return response()->json([
                'status'  => 'error',
                'message' => 'ไม่สามารถเล่นวิดีโอได้ในขณะนี้ กรุณาลองใหม่อีกครั้งหรือติดต่อเจ้าหน้าที่',
            ], 500);
                }

    }

    public function updateProgress(Request $request): JsonResponse
    {

        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Please log in again.'
            ], 401); // Return 401 แทนที่จะปล่อยให้ 500
        }

        $data = $request->only(['course_id', 'lesson_id', 'file_id', 'seconds', 'status']);
        // 2. เรียก Service โดยส่ง ID และ Array ข้อมูล
        $result = $this->progressService->updateVideoProgress(
            auth()->id(),
            $data
        );

        return response()->json([
            'status' => 'success',
            'data'   => $result
        ]);
    }

    public function downloadfile(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Please log in again.'
            ], 401); // Return 401 แทนที่จะปล่อยให้ 500
        }
        $file = FileDoc::where('id',$request->query('file_doc_id'))->first();

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $file_path = public_path('images/uploads/filedoc'.DIRECTORY_SEPARATOR. $file->filename);

        if (!file_exists($file_path)) {
            return response()->json(['error' => 'File not found on the server'], 404);
        }

        $data = $request->only(['course_id', 'lesson_id', 'file_doc_id']);
        $this->progressService->updateDocProgress(auth()->id(),$data);

        return response()->download($file_path, $file->original_filename);
    }

    public function coursequestion($course_id,$id,  Request $request){
        if(Auth::check()){
            $post_test = Manage::where(['id' => $id, 'active' =>'y'])->first();

            if($post_test == null){
                Session::flash('sweetAlert', [
                    'title' => 'ไม่มีแบบทดสอบ',
                    'text' => 'ไม่มีแบบทดสอบจากบทเรียน',
                    'icon' => 'warning'
                ]);
                return redirect()->route('course.lesson',['course_id' => $course_id,'id' =>$id]);
            }
            $group = Grouptesting::where(['group_id' => $post_test->group_id,'active' =>'y'])->get();
            $lesson = Lesson::where('id',$id)->first();
            $course = Course::where('course_id',$course_id)->first();
            $cate = Category::where('cate_id',$course->cate_id)->first();
            $model = Question::where(['group_id'=> $post_test->group_id,'active' =>'y'])->get();
            if($post_test->type == 'pre'){
                $breadcrumbs = [
                    ['name' => 'หลักสูตร', 'url' => url('/cateOnline/index')],
                    ['name' => $cate->cate_title, 'url' => url('//courseOnline/index/' . $cate->id)],
                    ['name' => $lesson->title, 'url' => url('//courseOnline/learn/' . $lesson->id)],
                    ['name' => 'แบบทดสอบก่อนเรียน', 'url' => null], // You can set the URL to null for the current page
                ];
            }else{
                $breadcrumbs = [
                    ['name' => 'หลักสูตร', 'url' => url('/cateOnline/index')],
                    ['name' => $cate->cate_title, 'url' => url('//courseOnline/index/' . $cate->id)],
                    ['name' => $lesson->title, 'url' => url('//courseOnline/learn/' . $lesson->id)],
                    ['name' => 'แบบทดสอบหลังเรียน', 'url' => null], // You can set the URL to null for the current page
                ];
            }

            return view("course.question",['group'=> $group,'lesson'=>$lesson,'course'=>$course,'cate'=>$cate,'breadcrumbs'=>$breadcrumbs,'model'=>$model]);
        }else{
            return redirect()->route('index');
        }

    }
    // course create to
    public function store(Request $request)
    {
        $chk = Images::where(['user_id' => Auth::user()->id,'image_time' => $request->input('time') ,'lesson_id' => $request->input('lesson'),'file_id' =>$request->input('file_id')])->first();
        if($chk != null){
            return response()->json(['message' => 'Image have been save'], 200);
        }else{
            // บันทึกข้อมูลลงในฐานข้อมูล
            $image = new Images();
            $image->image_time = $request->input('time'); // เปลี่ยนจาก 'image' เป็น 'time'
            $image->image_picture = $request->input('image');
            $image->user_id = Auth::user()->id;
            $image->lesson_id = $request->input('lesson');
            $image->file_id =  $request->input('file_id');
            $image->active =  'y';
            $image->save();



            return response()->json(['message' => 'Image saved successfully'], 200);
        }
    }

}
