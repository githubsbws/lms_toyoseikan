<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learn extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'learn';

    protected $primaryKey = 'learn_id';

    protected $fillable = [
        'learn_date',
        'lesson_status'
    ];

    public static function findById($id)
    {
        return static::where('learn_id', $id)->first();
    }
}
