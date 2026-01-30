<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orgchart extends Model
{
    use HasFactory;

    protected $table = 'orgchart';

    protected $primaryKey = 'id';

    protected $fillable = ['title','level','active'];

    public $timestamps = false;
    
    public static function findById($id)
    {
        return static::where('id', $id)->first();
    }

    public function users()
    {
        return $this->belongsToMany(Users::class, 'orgchart_id', 'user_id');
    }
}
