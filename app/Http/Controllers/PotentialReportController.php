<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Orgchart;
use App\Services\ReportPotentialService;
use Illuminate\Http\Request;

class PotentialReportController extends Controller
{
    public function __construct(
        protected ReportPotentialService $reportPotentialService,
    ) {}

    public function PotentialReport(Request $request)
    {
        if(auth()->check())
        {
            $userDetail = auth()->user();
            $courses = Course::where('create_by',$userDetail->id)->where('active','y')->get();
            $departments = Orgchart::where('id',$userDetail->department_org_id)->first();

            $potentialData = $this->reportPotentialService->getPotentialData($request);
            return view('admin.report.report_learning_potential',compact('potentialData','courses','departments'));
        }
        return redirect()->route('login.admin');
    }
}
