<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Roadmap;

class RoadmapService
{
    /**
     * Auto-generate roadmap and add course into roadmap
     */
    public function generateForCourse($courseId, $positionId, $years)
    {
        if (!$positionId || !$years) {
            return; 
        }

        $roadmap = Roadmap::firstOrCreate(
            [
                'position_id'       => $positionId,
                'years_of_service'  => $years
            ],
            [
                'title'       => "Learning Path ตำแหน่ง $positionId ($years ปี)",
                'active'      => 'y',
                'created_by'  => Auth::user()->id,
                'updated_by'  => Auth::user()->id
            ]
        );

        $lastOrder = DB::table('tbl_roadmap_course')
            ->where('roadmap_id', $roadmap->id)
            ->max('order');

        $nextOrder = $lastOrder ? $lastOrder + 1 : 1;

        DB::table('tbl_roadmap_course')->updateOrInsert(
            [
                'roadmap_id' => $roadmap->id,
                'course_id'  => $courseId
            ],
            [
                'order'     => $nextOrder,
                'mandatory' => 'y',
                'active'    => 'y'
            ]
        );
    }
}