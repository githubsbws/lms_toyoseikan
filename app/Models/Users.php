<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Learn;
use App\Models\Score;

class Users extends AuthenticatableUser implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'created_at', 'id', 'username', 'password', 'email',
        'org_id', 'division_id', 'position_id', 'pic_user', 'department_id', 'activkey', 'create_at', 'lastvisit_at', 'superuser', 'status', 'online_status', 'online_user', 'last_ip', 'last_activity',
        'avatar', '_token', 'group_id', 'del_status', 'asc_id','teacher_status','team_id'
    ];
    public static function findById($id)
    {
        return static::where('id', $id)->first();
    }
    public function Profiles()
    {
        return $this->hasOne(Profiles::class,'user_id','id');
    }
    public function isAdmin()
    {
        return $this->superuser;
    }
    public function isAdmin_group()
    {
        return $this->group_id;
    }

    public function Orgchart()
    {
        return $this->belongsTo(Orgchart::class,'org_id','id');
    }

    public function Asc()
    {
        return $this->belongsTo(ASC::class,'asc_id','id');
    }

    public function Position()
    {
        return $this->belongsTo(Position::class,'position_id','id');
    }

    public function PermissionGroup()
    {
        return $this->hasOne(Pgroup::class,'id','group_id');
    }

    public function orgcharts()
    {
        return $this->belongsToMany(OrgchartUser::class,'user_id', 'orgchart_id');
    }
    public function learns()
    {
        return $this->hasMany(Learn::class, 'user_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id', 'id');
    }

    public function Team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
