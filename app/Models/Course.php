<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course_online';

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_lecturer',
        'active',
        'recommend',
        'status',
        'cate_amount',
        'time_test',
        'lang_id',
        'random_choice',
        'average_time_pretest',
        'average_time_posttest',
        'course_picture'
    ];

    const CREATED_AT = 'create_date'; // Custom created_at column
    const UPDATED_AT = 'update_date'; // Custom update_at column
    const BOOK_AT = 'course_book_date'; // Custom created_at column
    const RECTOR_AT = 'course_rector_date'; // Custom update_at column

    public static function findById($id)
    {
        return static::where('course_id', $id)->first();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'course_lecturer','teacher_id');
    }
}
