<?php

namespace App\Http\Controllers;

use App\Models\Conditions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Exports\ReportUserExport;
use App\Exports\ReportLicenseExport;
use App\Exports\PersonalAssessmentDetailExport;
use App\Models\Users;
use App\Models\Course;
use App\Models\Learn;
use App\Models\OperationMachine;
use App\Models\ParameterSetting;
use App\Models\LicensePerson;
use App\Models\PersonalAssessment;
use Maatwebsite\Excel\Facades\Excel;

class ReportExcelController extends Controller
{
    public function export(Request $request)
    {
        $line_id = $request->line_id;
        $section_id = $request->section_id;
        $team_id = $request->team_id;

        $usersQuery = Users::with('Orgchart')
            ->join('profiles','profiles.user_id','=','users.id')
            ->leftJoin('orgchart','orgchart.id','=','users.org_id')
            ->where('users.status', '1');

        if($line_id){
            $usersQuery->where('orgchart.line_id', $line_id);
        }

        if($section_id){
            $usersQuery->where('orgchart.section_id', $section_id);
        }

        if($team_id){
            $usersQuery->where('orgchart.team_id', $team_id);
        }

        $users = $usersQuery
            ->select(
                'users.*',
                'profiles.firstname',
                'profiles.lastname'
            )
            ->orderBy('users.id', 'DESC')
            ->get();

        $courses = Course::join(
                'category',
                'category.cate_id',
                '=',
                'course_online.cate_id'
            )
            ->where('course_online.active','y')
            ->select(
                'course_online.course_id',
                'course_online.course_title',
                'course_online.cate_id',
                'category.cate_title as cate_name'
            )
            ->orderBy('course_online.cate_id')
            ->get();

        $groupedCourses = $courses->groupBy('cate_name');

        $learns = Learn::whereIn(
                'course_id',
                $courses->pluck('course_id')
            )
            ->whereIn(
                'user_id',
                $users->pluck('id')
            )
            ->get()
            ->groupBy('user_id');

        return Excel::download(
            new ReportUserExport(
                $users,
                $groupedCourses,
                $learns
            ),
            'report_user.xlsx'
        );
    }

    public function report_license_export(Request $request)
    {
        $line_id = $request->line_id;
        $section_id = $request->section_id;
        $team_id = $request->team_id;

        $usersQuery = Users::join('profiles','profiles.user_id','=','users.id')
            ->leftJoin('orgchart','orgchart.id','=','users.org_id')
            ->where('users.status', '1');

        if($line_id){
            $usersQuery->where('orgchart.line_id', $line_id);
        }

        if($section_id){
            $usersQuery->where('orgchart.section_id', $section_id);
        }

        if($team_id){
            $usersQuery->where('orgchart.team_id', $team_id);
        }

        $users = $usersQuery
            ->select(
                'users.*',
                'profiles.firstname',
                'profiles.lastname'
            )
            ->orderBy('users.id', 'DESC')
            ->get();

        $operateMachines = OperationMachine::where('active','y')->get();

        $parameterSettings = ParameterSetting::where('active','y')->get();

        $licenses = LicensePerson::whereIn('user_id', $users->pluck('id'))
            ->get()
            ->groupBy('user_id');

        return Excel::download(
            new ReportLicenseExport(
                $users,
                $operateMachines,
                $parameterSettings,
                $licenses
            ),
            'report_license.xlsx'
        );
    }

    public function detail_export($id)
    {
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

        return Excel::download(
            new PersonalAssessmentDetailExport(
                $user,
                $assessments
            ),
            'personal_assessment_'.$user->staff_id.'.xlsx'
        );
    }
}
