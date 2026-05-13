<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class ReportPotentialService
{
    const STATUS_ACTIVE = 'y';

    public function getPotentialData(Request $request)
    {
        $currentYear = now()->year;

        // เริ่มต้นที่ Course และกรองเฉพาะคอร์สที่ต้องการเหมือนเดิม
        $query = Course::where('active', self::STATUS_ACTIVE);

        // ใช้ whereHas กรอง "ผู้เรียน" ตามเงื่อนไขการค้นหา
        $query->whereHas('passcourse', function($q) use ($request, $currentYear) {
            $q->where('academic_year', $currentYear);

            // มุดลงไปกรองที่ตัว User
            $q->whereHas('user', function($userQuery) use ($request) {

                // 1. ค้นหา ชื่อ-นามสกุล (ข้ามไปค้นใน table Profiles)
                if ($request->filled('fullname')) {
                    $userQuery->whereHas('Profiles', function($p) use ($request) {
                        $p->where(function($pName) use ($request) {
                            $pName->where('firstname', 'like', '%' . $request->fullname . '%')
                                ->orWhere('lastname', 'like', '%' . $request->fullname . '%');
                        });
                    });
                }

                // 2. ค้นหาโดย team_id (อยู่ใน table user โดยตรง)
                $userQuery->where(function($uq) use ($request) {

                // 1. ระดับ Line: หา User ที่ Position (org_id) มี Parent เป็น Line ID นี้
                if ($request->filled('line_id')) {
                    $uq->whereHas('orgchart', function($org) use ($request) {
                        $org->where('parent_id', $request->line_id);
                    });
                }

                // 2. ระดับ Section: มุดจาก Position -> Line -> Section
                elseif ($request->filled('section_id')) {
                    $uq->whereHas('orgchart', function($org) use ($request) {
                        // มุดชั้นที่ 1: หาว่า Position นี้ สังกัด Line อะไร (parent_id)
                        // มุดชั้นที่ 2: หาว่า Line นั้น สังกัด Section ID ที่เราเลือกมาหรือไม่ (whereIn)
                        $org->whereIn('parent_id', function($lineQuery) use ($request) {
                            $lineQuery->select('id')
                                    ->from('orgchart')
                                    ->where('parent_id', $request->section_id); // parent_id ของ Line คือ Section ID
                        });
                    });
                }

                // 3. ระดับ Department: ตรงๆ ตามที่น้องบอก
                elseif ($request->filled('department_id')) {
                    $uq->where('department_org_id', $request->department_id);
                }
            });
            });
        });

        // อย่าลืมดึงข้อมูลพ่วง (Eager Load) ให้ครบเหมือนเดิม
        $results = $query->with([
            'passcourse' => function($q) use ($currentYear, $request) {
                $q->where('academic_year', $currentYear)
                ->with(['user.Profiles', 'user.orgchart']);

                // หมายเหตุ: ตรงนี้ต้องใส่ Filter ชุดเดียวกับข้างบนด้วย
                // เพื่อให้รายชื่อคนที่โชว์ในตาราง ถูกกรองตามที่ Search จริงๆ
            }
        ])->get();

        return $results;
    }
}
