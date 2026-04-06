<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    protected $table = 'roadmap';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'org_id','active', 'created_by', 'updated_by','department_org_id'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'roadmap_course', 'roadmap_id', 'course_id')
                    ->withPivot(['order','mandatory','active'])
                    ->orderBy('pivot_order', 'asc');
    }

}
