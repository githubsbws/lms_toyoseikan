<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Learn;
use App\Models\Orgchart;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    /**
     * Entry point เดียวให้ Controller เรียก
     *
     * $filters รองรับ (ยังไม่ต้อง wire กับ UI ตอนนี้ แต่เตรียม signature ไว้
     * เพื่อให้แถบค้นหาบนสุดต่อสายเข้ามาได้ทีหลังโดยไม่ต้องแก้ signature):
     *   - department_id
     *   - section_id
     *   - line_id
     *   - team_id
     *   - date_from, date_to
     *   - search
     *
     * @return array แพ็กเก็ตข้อมูลแยกตามกลุ่มการ์ด/แถวใน view (1 key = 1 การ์ดหรือ 1 ตาราง)
     */
    public function getDashboardData(array $filters = []): array
    {
        return [
            // แถวที่ 1: การ์ดสรุปตัวเลข 4 ใบ
            'overview' => $this->getOverviewStats($filters),

            // แถวที่ 3 การ์ดที่ 1: คอร์สที่ต้องติดตาม
            'overdueCourses' => $this->getOverdueCourses($filters),

            // แถวที่ 4 ตารางที่ 1: การเรียนรู้ตามแผนก
            // 'departmentLearning' => $this->getDepartmentLearning($filters),

            // แถวที่ 4 การ์ดที่ 2: หลักสูตรที่มีผู้เรียนมากที่สุด
            // 'popularCourses' => $this->getPopularCourses($filters),
        ];
    }

    /**
     * แถวที่ 1 (4 การ์ด): คอร์สทั้งหมด / เนื้อหาทั้งหมด / ผู้ใช้ทั้งหมด / รออนุมัติ
     *
     * ใช้ DB::table()->count() แทน raw SQL string เพราะ connection 'pgsql'
     * ตั้ง prefix ('tbl_') ไว้ใน config/database.php — query builder/Eloquent
     * จะเติม prefix ให้เองตอน compile แต่ raw SQL string ไม่ผ่านการเติม prefix นี้
     * (นี่คือสาเหตุของ error "relation course_online does not exist" ก่อนหน้านี้)
     *
     * แลกกับ 4 round-trip แทน 1 แต่ปลอดภัยกว่าและ apply filter ต่อได้ง่ายกว่า raw SQL
     *
     * NOTE: ยังไม่ apply $filters ที่นี่ เพราะการ์ดสรุประดับบนสุดมักหมายถึง
     * "ทั้งระบบ" — ถ้าต้องการให้ตัวเลขนี้ไหวตามแผนก/ทีมที่เลือกด้วย
     * ต้องเพิ่ม WHERE ตามคีย์ที่เกี่ยวข้องในแต่ละ query (แจ้งได้ ผมจะเติมให้)
     */
    private function getOverviewStats(array $filters): array
    {
        return [
            'total_courses'     => DB::table('course_online')->where('active', 'y')->count(),
            'total_files'       => DB::table('file')->where('active', 'y')->count(),
            'total_users'       => DB::table('users')->where('status', '1')->count(),
            'pending_approvals' => DB::table('log_approve')->count(),
        ];
    }

    /**
     * คอร์สที่ต้องติดตาม (Overdue): คอร์สที่ end_date ผ่านมาแล้ว
     * และยังมีผู้เรียนที่ยังไม่ผ่าน (passcourse ยัง pass ไม่ครบ)
     *
     * ASSUMPTION ที่ต้อง confirm: ตอนนี้ผมนับ "ผู้เรียนที่เกี่ยวข้อง" จาก
     * users ที่ department_org_id ตรงกับ course.department_org_id
     * (เพราะ course_online มี department_org_id ตรงตัวอยู่แล้ว)
     *
     * ถ้า business rule จริง ๆ ต้องเทียบจาก roadmap ของผู้เรียนแต่ละคน
     * (แบบเดียวกับ ManagerDashboardService::getNearExpireCourses) จะซับซ้อนกว่านี้
     * และต้อง build map ทั้งองค์กรแบบ PHP-side — บอกได้เลยถ้าต้องใช้แบบนั้น
     */
    private function getOverdueCourses(array $filters, int $limit = 5)
    {
        // NOTE: ใช้ whereHas (สร้าง EXISTS subquery) แทน having() บน alias จาก withCount
        // เพราะ PostgreSQL ไม่ยอมให้ HAVING อ้างชื่อ alias ที่มาจาก SELECT ได้ (ต่างจาก MySQL)
        // withCount ยังคงไว้เพื่อดึงตัวเลข unfinished ไปแสดงผล
        return Course::where('active', 'y')
            ->whereDate('end_date', '<', now())
            ->whereHas('passcourse', function ($q) {
                $q->where('passcours_status', '!=', 'pass');
            })
            ->select('course_id', 'course_title', 'end_date', 'department_org_id')
            ->withCount([
                'passcourse as unfinished_count' => function ($q) {
                    $q->where('passcours_status', '!=', 'pass');
                },
            ])
            ->orderBy('end_date')
            ->limit($limit)
            ->get()
            ->map(fn ($course) => [
                'course_id'   => $course->course_id,
                'title'       => $course->course_title,
                'deadline'    => $course->end_date,
                'unfinished'  => $course->unfinished_count,
            ]);
    }

    /**
     * ตาราง "การเรียนรู้ตามแผนก": ผู้เรียน / Completion Rate / Pass Rate / คอร์สที่กำลังเรียน
     * group ตาม department (orgchart level = 3) ในคำสั่งเดียว ไม่ loop ทีละแผนก
     *
     * TODO ต้องยืนยัน business rule ก่อนเติมตัวเลขจริง:
     *   - "Completion Rate" นับจากคอร์สทั้งหมดที่แผนกเปิด หรือจาก roadmap ภาคบังคับ?
     *   - "Pass Rate" ต่างจาก Completion Rate ตรงไหน (สอบผ่าน vs เรียนจบ)?
     * ตอนนี้ผมเว้น query โครงไว้ + นับแค่ผู้เรียน (ตัวเลขที่ไม่กำกวม) เพื่อไม่เดา rate ผิด
     */
    // private function getDepartmentLearning(array $filters)
    // {
    //     return DB::table('orgchart as o')
    //         ->leftJoin('users as u', function ($join) {
    //             $join->on('u.department_org_id', '=', 'o.id')
    //                  ->where('u.status', '1');
    //         })
    //         ->where('o.level', 3) // level 3 = แผนก ตาม DashboardService::getEmployeePosition
    //         ->where('o.active', 'y')
    //         ->groupBy('o.id', 'o.title')
    //         ->havingRaw('COUNT(u.id) > 0')
    //         ->select([
    //             'o.title as department',
    //             DB::raw('COUNT(u.id) as learner_count'),
    //             // completion_rate / pass_rate / active_courses: รอ confirm สูตรคำนวณ
    //         ])
    //         ->get();
    // }

    /**
     * หลักสูตรที่มีผู้เรียนมากที่สุด: จำนวนผู้เรียน distinct ต่อคอร์ส (นับจาก learn)
     * เรียงจากมากไปน้อย จำกัด top N — group ที่ DB ครั้งเดียว
     */
    // private function getPopularCourses(array $filters, int $limit = 5)
    // {
    //     return Learn::join('course_online as c', 'c.course_id', '=', 'learn.course_id')
    //         ->select('c.course_id', 'c.course_title')
    //         ->selectRaw('COUNT(DISTINCT learn.user_id) as learner_count')
    //         ->groupBy('c.course_id', 'c.course_title')
    //         ->orderByDesc('learner_count')
    //         ->limit($limit)
    //         ->get()
    //         ->map(function ($row, $index) {
    //             return [
    //                 'rank'          => $index + 1,
    //                 'course_id'     => $row->course_id,
    //                 'title'         => $row->course_title,
    //                 'learner_count' => $row->learner_count,
    //             ];
    //         });
    // }
}
