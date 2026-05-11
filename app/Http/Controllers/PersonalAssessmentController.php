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
use App\Models\PersonalAssessment;

class PersonalAssessmentController extends Controller
{
    public function index()
    {
        if(AuthFacade::useradmin()){

            $users = Users::join(
                    'profiles',
                    'profiles.user_id',
                    '=',
                    'users.id'
                )
                ->where('users.status',1)
                ->select(
                    'users.id',
                    'users.staff_id',
                    'profiles.firstname',
                    'profiles.lastname'
                )
                ->orderBy('users.id','DESC')
                ->get();

            return view(
                'admin.report.personalassessment.index',
                compact('users')
            );
        }

        return redirect()->route('login.admin');
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
