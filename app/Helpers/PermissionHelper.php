<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class PermissionHelper
{
    public static function can($menuKey)
    {
        if (!Auth::check()) return false;

        $user = Auth::user();

        if($user->teacher_status == 1 && in_array($menuKey,[10,11,12,13])){
            return true;
        }

        return Permission::where('group_id', $user->group_id)
            ->where('group_parent_id', $menuKey)
            ->where('active', 1)
            ->exists();
    }
}