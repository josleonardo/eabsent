<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request, ActivityLogService $activityLogService)
    {
        $user = $request->user();

        $logs = $activityLogService->getLogs($user);

        return view('reports.activity-logs.index', ['pageName' => 'Activity Logs'] + compact('logs'));
    }
}
