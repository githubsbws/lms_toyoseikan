<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passcourse extends Model
{
    use HasFactory;

    protected $table = 'passcours';

    protected $primaryKey = 'passcours_id';
    // public $timestamps = false;

    protected $fillable = [
        'passcours_cates',
        'passcours_course',
        'passcours_user',
        'passcours_date',
        'passcours_status',
        'academic_year'
    ];

    public function user()
    {
        return $this->hasOne(Users::class,'id','user_id');
    }
    public function courseonline()
    {
        return $this->hasOne(Course::class,'course_id','passcours_course');
    }

}
