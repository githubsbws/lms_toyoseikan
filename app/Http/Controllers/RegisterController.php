<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Profiles;
use App\Models\Line;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use App\Models\Orgchart;
use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    //----- หน้า login
    function registers()
    {
        $orgchart = Orgchart::where('active','y')
                    ->where('level',2)
                    ->get();

        return view('reg.register',compact('orgchart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'orgchart_id' => 'required',
            'line_id' => 'required',
            'department_id' => 'required',
            'section_id' => 'required',
            'team_id' => 'required',
            'position_id' => 'required',
        ]);

        $user = new Users();

        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;

        $user->company_id = $request->orgchart_id;
        $user->line_id = $request->line_id;
        $user->department_id = $request->department_id;
        $user->division_id = $request->section_id;
        $user->team_id = $request->team_id;
        $user->position_id = $request->position_id;

        $user->save();

        $profile = new Profiles();

        $profile->user_id = $user->id;
        $profile->firstname = $request->firstname;
        $profile->lastname = $request->lastname;

        $profile->save();


        return redirect()->back()->with('success','สมัครสมาชิกเรียบร้อย');
    }

    public function getSubOrg($parentId)
    {
        $subs = Orgchart::where('parent_id', $parentId)
                        ->where('active', 'y')
                        ->get(['id', 'title']);

        return response()->json([
            'data' => $subs,
            'has_child' => $subs->isNotEmpty() // ส่งสถานะไปบอกว่ามีลูกต่อไหม
        ]);
    }
}
