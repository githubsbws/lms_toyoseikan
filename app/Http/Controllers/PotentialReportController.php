<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PotentialReportController extends Controller
{
    public function PotentialReport()
    {
        if(auth()->check())
        {
            return view('admin.report.report_learning_potential');
        }
        return redirect()->route('login.admin');
    }
}
