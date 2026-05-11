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
use App\Models\OperationMachine;
use App\Models\ParameterSetting;
use App\Models\LicensePerson;

class ReportLicensePersonController extends Controller
{
    function report_license(Request $request) {

        if(AuthFacade::useradmin()){

            $users = Users::join('profiles','profiles.user_id','=','users.id')
                ->where('users.status', '1')
                ->select(
                    'users.*',
                    'profiles.firstname',
                    'profiles.lastname'
                )
                ->orderBy('users.id', 'DESC')
                ->get();

            $operateMachines = OperationMachine::where('active','y')->get();

            $parameterSettings = ParameterSetting::where('active','y')->get();

            $licenses = LicensePerson::get()
                ->groupBy('user_id');

            return view("admin.report.report_license", [
                'users' => $users,
                'operateMachines' => $operateMachines,
                'parameterSettings' => $parameterSettings,
                'licenses' => $licenses
            ]);

        }

        return redirect()->route('login.admin');
    }
}
