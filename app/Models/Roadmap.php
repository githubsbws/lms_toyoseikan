<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    protected $table = 'roadmap';
    protected $primaryKey = 'id';

    protected $fillable = [
        'position_id', 'years_of_service', 'title',
        'active', 'created_by', 'updated_by'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'roadmap_course', 'roadmap_id', 'course_id')
                    ->withPivot(['order','mandatory','active'])
                    ->orderBy('pivot_order', 'asc');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}