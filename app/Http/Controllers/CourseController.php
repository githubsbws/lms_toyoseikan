<?php

namespace App\Http\Controllers;

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
use App\Models\OrgchartUser;
use App\Models\Roadmap;
use App\Models\RoadmapCourse;
use App\Models\Users;
use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    // Detail
    function courseDetail($id)
    {
    if(Auth::check()){
        $users = Users::with('organization.parent')->get();
        $course_detail = Course::findById($id);
        $course_lesson = Lesson::where(['course_id' => $course_detail->course_id,'active' => 'y'])->first();
        if($course_lesson != null){
            return view("course.course-detail",['course_detail' =>$course_detail],['course_lesson' =>$course_lesson]);
        }else{
            session()->flash('error', 'บทเรียนยังไม่เปิดให้เรียนตอนนี้');
            return redirect()->route('course');
        }
    }else{
        return redirect()->route('index');
    }
    //    dd($course_detail);
    }

    // Lession
    function courseLesson($course_id,$id, Request $request,$files = null){
        $domain = '15';
        if(Auth::check()){
        $ptest = Manage::where(['type' => 'pre','id' => $id,'active' =>'y'])->first();

        $chk_score = Score::where(['lesson_id'=>$id,'user_id'=>Auth::user()->id,'active'=>'y','course_id'=>$course_id])->orderBy('update_date','DESC')->first();
        // dd($chk_score->toArray());
        if($chk_score == null){
            if($ptest){
                return redirect()->route("course.coursequestion",['course_id' =>$course_id,'id'=>$id]);
            }
        }
        $learnModel = Learn::where(['lesson_id' => $id,'user_id' => Auth::user()->id])->first();
        if(!$learnModel){
            $learnLog = new Learn;
            $learnLog->user_id = Auth::user()->id;
            $learnLog->gen_id = '0';
            $learnLog->lesson_id = $id;
            $learnLog->course_id = $course_id;
            $learnLog->learn_date = now();
            $learnLog->create_date = now();
            $learnLog->domain_id = $domain;
            $learnLog->save();
            $learn_id = $learnLog->learn_id;

        }
        else
        {
            // $learnModel->update([
            //     'learn_date' => now()
            // ]);
            $learn = Learn::findById($learnModel->learn_id);
            $learn->fill([
                'learn_date' => now()
            ]);
            $learn->save();
            $learn_id = $learnModel->learn_id;
        }
        $course_detail = Course::findById($course_id);
        $lesson_list = Lesson::where(['course_id' => $course_id,'active' =>'y'])->get();
        $file = FileDoc::where(['lesson_id' => $id,'active' =>'y'])->get();

        if(!$lesson_list || $lesson_list == null){
            session()->flash('error', 'บทเรียนยังไม่เปิดให้เรียนตอนนี้');
            return redirect()->route('course');
        }
        if(!$file || $file == null){
            session()->flash('error', 'บทเรียนยังไม่เปิดให้เรียนตอนนี้');
            return redirect()->route('course');
        }
        if(isset($id)){
            $course_lesson = Lesson::join('course_online','course_online.course_id','=','lesson.course_id')->where('lesson.id',$id)->get();
            $track = File::where('lesson_id',$id)->first();

            if($files != null){
                $file_id = File::where('id',$files)->where('active','y')->first();
                // dd($file_id->toArray());
            }else{
                $file_id = File::where('lesson_id',$id)->where('active','y')->first();
                if(!$file_id){
                    session()->flash('error', 'บทเรียนยังไม่เปิดให้เรียนตอนนี้');
                    return redirect()->route('course');
                }
            }
            // dd($file_id->id);
        }
        return view("course.course-lesson",['course_lesson' =>$course_lesson,'course_detail' =>$course_detail,'lesson_list' =>$lesson_list,'file' =>$file,'course_id' =>$course_id,'lesson_id' =>$id,'learn_id' =>$learn_id,'file_id' =>$file_id,'track' => $track]);
    }else{
        return redirect()->route('index');
    }
}

    // course
    function course(Request $request)
    {
        if(Auth::check()){
            if(Auth::user()->team_id == 7){
                $orgCourseIds = Roadmap::with(['roadmapCourse' => fn($q) => $q->orderBy('order', 'asc')])->where('line_id',Auth::user()->Orgchart->line->id)->get();

            }else{
                $orgCourseIds = Orgcourse::where('orgchart_id',Auth::user()->org_id)->pluck('course_id');;
            }


            $course_detail = Course::whereIn('course_id', $orgCourseIds)
                ->where('active', 'y') // เผื่ออยากดึงเฉพาะที่เปิดใช้งานอยู่
                ->paginate(10);

            return view("course.course",['course_detail' =>$course_detail]);
        }else{
            return redirect()->route('index');
        }
    }
    function LearnVdo($id,$learn_id,$counter, Request $request)
    {
        $learnVdoModel = LearnFile::where(['learn_id'=>$learn_id,'file_id'=>$id])->first();

        if($learnVdoModel){
            if($counter == 'success' || $learnVdoModel->learn_file_status == 's')
                {
                    $learnVdoModel->fill([
                        'learn_file_status' => 's'
                    ]);
                    $learnVdoModel->save();

                    $chk_learn = Learn::findById($learn_id);
                    if($chk_learn->lesson_status !== "pass"){
                        $chk_learn->fill([
                            'lesson_status' => 'pass'
                        ]);

                        $chk_learn->save();
                    }
                    $att['no']      = $id;
                    $att['image']   = '<img src="' . asset('images/icon_checkpast.png') . '" alt="ผ่าน" title="ผ่าน" style="margin-bottom: 8px;">';

                    echo json_encode($att);
                }
            elseif($counter == 'counter' || $learnVdoModel->learn_file_status == 'l'){
                $view = File::findById($id);
                $views = $view->views+1;
                $view->fill([
                    'views' => $views
                ]);
                $view->save();
            }
        }else{
            $learnLog = new LearnFile;
            $learnLog->learn_id = $learn_id;
            $learnLog->user_id_file = Auth::user()->id;
            $learnLog->file_id = $id;
            $learnLog->learn_file_date = now();
            $learnLog->learn_file_status = "l";
            $learnLog->save();

            $chk_learn = Learn::findById($learn_id);
            $chk_learn->fill([
                'lesson_status' => 'learning'
            ]);

            $chk_learn->save();

            $att['no']      = $id;
            $att['image']   = '<img src="' . asset('images/icon_checklost.png') . '" alt="เรียนยังไม่ผ่าน" title="เรียนยังไม่ผ่าน" style="margin-bottom: 8px;">';

            echo json_encode($att);
        }
    }

    public function downloadfile($id,  Request $request)
{
     // Retrieve the file information from the database
     $file = FileDoc::where('id',$id)->first();
     // Check if the file exists

     // Construct the full file path
     $file_path = public_path('images/uploads/filedoc'.DIRECTORY_SEPARATOR. $file->filename);


     // Check if the file actually exists on the server
     if (!file_exists($file_path)) {
         return response()->json(['error' => 'File not found on the server'], 404);
     }

     // Generate the response for downloading the file
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
