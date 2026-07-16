<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Orgchart;
use App\Models\Orgcourse;
use App\Models\Lesson;
use App\Models\OrgchartUser;
use App\Models\Roadmap;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Services\ManagerDashboardService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function dashboard(DashboardService $dashboardService)
    {
        if (!Auth::check()) {
            return view('auth.login');
        }

        $dashboard = $dashboardService->getEmployeeDashboard(Auth::user());

        $news = News::where('active', 'y')
                ->orderBy('create_date', 'desc')
                ->limit(3)
                ->get();


        return view('dashboard.dashboard', [
            'dashboard' => $dashboard,
            'news' => $news
        ]);
    }

    public function teamLearningAjax(Request $request, ManagerDashboardService $ManagertDashboardService)
    {
        $teamLearning = $ManagertDashboardService->getTeamLearningProgress(
            Auth::user(),
            $request->keyword
        );

        return view(
            'admin.index.partials.team-learning',
            compact('teamLearning')
        );
    }

}
