<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseScoreWeight extends Model
{
    use HasFactory;

    protected $table = 'course_score_weight';
    protected $primaryKey = 'id';

    protected $fillable = [
        'course_id',
        'q_a_weight',
        'operate_weight',
        'assigh_weight',
        'observe_weight',
        'exam_weight'
    ];
}
