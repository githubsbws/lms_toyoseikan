<?php

namespace App\Http\Controllers;

use App\Models\Conditions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Facades\AuthFacade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\Users;
use App\Models\Orgchart;
use App\Models\Team;
use App\Models\OperationMachine;
use App\Models\ParameterSetting;
use App\Models\LicensePerson;

class ReportLicensePersonController extends Controller
{
    public function report_license(Request $request)
    {
        if(AuthFacade::useradmin()){

            $line_id = $request->line_id;
            $section_id = $request->section_id;
            $team_id = $request->team_id;

            $userDetail = auth()->user();

            /*
            |--------------------------------------------------------------------------
            | Users Query
            |--------------------------------------------------------------------------
            */

            $usersQuery = Users::join(
                    'profiles',
                    'profiles.user_id',
                    '=',
                    'users.id'
                )
                ->leftJoin(
                    'orgchart',
                    'orgchart.id',
                    '=',
                    'users.org_id'
                )
                ->where('users.status', '1');

            /*
            |--------------------------------------------------------------------------
            | Section Filter
            |--------------------------------------------------------------------------
            */

            if($section_id){

                $lineIds = Orgchart::where(
                        'parent_id',
                        (string)$section_id
                    )
                    ->where('active','y')
                    ->pluck('id');

                $usersQuery->whereIn(
                    'users.org_id',
                    $lineIds
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Line Filter
            |--------------------------------------------------------------------------
            */

            if($line_id){

                $usersQuery->where(
                    'users.org_id',
                    $line_id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Team Filter
            |--------------------------------------------------------------------------
            */

            if($team_id){

                $usersQuery->where(
                    'orgchart.team_id',
                    $team_id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            $users = $usersQuery
                ->select(
                    'users.*',
                    'profiles.firstname',
                    'profiles.lastname'
                )
                ->orderBy('users.id', 'DESC')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Operate Machines
            |--------------------------------------------------------------------------
            */

            $operateMachines = OperationMachine::where(
                    'active',
                    'y'
                )
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Parameter Settings
            |--------------------------------------------------------------------------
            */

            $parameterSettings = ParameterSetting::where(
                    'active',
                    'y'
                )
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Licenses
            |--------------------------------------------------------------------------
            */

            $licenses = LicensePerson::whereIn(
                    'user_id',
                    $users->pluck('id')
                )
                ->get()
                ->groupBy('user_id');

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            $departments = Orgchart::where(
                    'id',
                    $userDetail->department_org_id
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Sections
            |--------------------------------------------------------------------------
            */

            $sections = Orgchart::where(
                    'parent_id',
                    $userDetail->department_org_id
                )
                ->where('active','y')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Lines
            |--------------------------------------------------------------------------
            */

            $lines = collect();

            if($section_id){

                $lines = Orgchart::where(
                        'parent_id',
                        (string)$section_id
                    )
                    ->where('active','y')
                    ->get();
            }

            /*
            |--------------------------------------------------------------------------
            | Teams
            |--------------------------------------------------------------------------
            */

            $teams = Team::where('active','y')->get();

            return view("admin.report.report_license", [

                'users' => $users,
                'operateMachines' => $operateMachines,
                'parameterSettings' => $parameterSettings,
                'licenses' => $licenses,

                'departments' => $departments,
                'sections' => $sections,
                'lines' => $lines,
                'teams' => $teams,

                'line_id' => $line_id,
                'section_id' => $section_id,
                'team_id' => $team_id
            ]);

        }

        return redirect()->route('login.admin');
    }

    public function getLines($section_id)
    {
        $lines = Orgchart::where(
                'parent_id',
                (string)$section_id
            )
            ->where('active', 'y')
            ->get(['id', 'title']);

        return response()->json($lines);
    }
}
