<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseScore extends Model
{
    use HasFactory;

    protected $table = 'coursescore';

    protected $primaryKey = 'score_id';

    protected $fillable = [
       'course_id',
       'user_id',
       'score_number',
       'score_total',
       'score_status',
       'pass_year'
    ];


}
