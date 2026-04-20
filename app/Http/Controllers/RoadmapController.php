<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Roadmap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoadmapController extends Controller
{
    public function indexNewEmp() {
        $newEmpRoadmap = Roadmap::with('roadmapCourse')
                                ->where('department_org_id',Auth::user()->department_org_id)
                                ->where('active','y')
                                ->first();
        return view('admin.roadmap.newemp',compact('newEmpRoadmap'));
    }

    public function createNewEmp(Request $request) {
        $newEmpCourse = Course::where('is_onboarding',true)
                        ->where('active','y')
                        ->where('department_org_id',Auth::user()->department_org_id)
                        ->get();
        if($request->isMethod('post')){
            $roadmapItems = json_decode($request->roadmap_items, true);
            // dd($roadmapItems);
            DB::beginTransaction();
            try {
                // 2. สร้างหัวข้อ Roadmap หลักก่อน
                $roadmap = new Roadmap();
                $roadmap->name = Auth::user()->Department->title . ' Roadmap';
                $roadmap->active = 'y';
                $roadmap->created_by = Auth::user()->id;
                $roadmap->updated_by = Auth::user()->id;
                $roadmap->department_org_id =  Auth::user()->department_org_id;
                $roadmap->save();
                // 3. วนลูปบันทึกแต่ละวิชาลงในตาราง tbl_roadmap_course
                foreach ($roadmapItems as $item) {
                    DB::table('roadmap_course')->insert([
                        'roadmap_id'     => $roadmap->id,
                        'course_id'      => $item['course_id'],
                        // ถ้าใน JSON เป็น null ตัว DB จะบันทึกเป็น NULL ให้ตามที่น้องต้องการเป๊ะ
                        'milestone_days' => $item['milestone_days'],
                        'order'          => $item['order'],
                        'active'         => 'y',
                    ]);
                }
                DB::commit();
                return redirect()->route('roadmap.newemp.index')->with('success', 'สร้าง Roadmap สำเร็จ!');
            }catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }
        return view('admin.roadmap.newemp_create',compact('newEmpCourse'));
    }

    public function editNewEmp(Request $request,$id) {
        $newEmpCourseEdit = Roadmap::findOrFail($id);
        if($request->isMethod('post')){
            $roadmapItems = json_decode($request->roadmap_items, true);
            $now = Carbon::now();
            // dd($roadmapItems);
            DB::beginTransaction();
            try {
                // 3. วนลูปบันทึกแต่ละวิชาลงในตาราง tbl_roadmap_course
                foreach ($roadmapItems as $item) {
                    DB::table('roadmap_course')
                    ->where('roadmap_id',$id)
                    ->where('course_id',$item['course_id'])
                    ->update([
                        'milestone_days' => $item['milestone_days'],
                        'order'          => $item['order'],
                    ]);
                }
                DB::table('roadmap')
                    ->where('id', $id)
                    ->update([
                        'updated_at' => $now,
                        // 'updated_by' => auth()->id(), // (แถม) ถ้ามีคอลัมน์คนแก้ก็ใส่ไปด้วยเลยครับ
                    ]);
                DB::commit();
                return redirect()->route('roadmap.newemp.index')->with('success', 'สร้าง Roadmap สำเร็จ!');
            }catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }
        return view('admin.roadmap.newemp_edit',compact('newEmpCourseEdit'));
    }
    public function indexGeneralEmp() {

    }
}
