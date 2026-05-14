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
use App\Models\PersonalAssessment;

class PersonalAssessmentController extends Controller
{
    public function index(Request $request)
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
                ->where('users.status',1);

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
                    'users.id',
                    'users.staff_id',
                    'profiles.firstname',
                    'profiles.lastname'
                )
                ->orderBy('users.id','DESC')
                ->get();

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

            return view(
                'admin.report.personalassessment.index',
                compact(
                    'users',

                    'departments',
                    'sections',
                    'lines',
                    'teams',

                    'line_id',
                    'section_id',
                    'team_id'
                )
            );
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
    
    public function detail($id)
    {
        if(AuthFacade::useradmin()){

            $user = Users::with([
                    'Profiles',
                    'Orgchart'
                ])
                ->findOrFail($id);

            $assessments = PersonalAssessment::with([
                    'topic'
                ])
                ->where('user_id',$id)
                ->orderBy('assessment_date')
                ->get();

            return view(
                'admin.report.personalassessment.detail',
                compact(
                    'user',
                    'assessments'
                )
            );
        }

        return redirect()->route('login.admin');
    }

}
