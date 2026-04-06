<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadmapCourse extends Model
{
    protected $table = 'roadmap_course';

    protected $primaryKey = 'id';

    protected $fillable = [
        'roadmap_id', 'course_id', 'order',
        'milestone_days', 'active'
    ];
}
