<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        // Allow only users with VEO or Admin role
        if (!$request->user() || (!$request->user()->isVeo() && !$request->user()->isAdmin())) {
            abort(403, 'Unauthorized access');
        }

        $query = ActivityLog::with('user')->latest();

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date-from
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter by date-to
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);
        $users = \App\Models\User::select('id', 'name', 'email')->get();
        $actions = ActivityLog::distinct()->pluck('action');

        return view('activity-logs.index', compact('logs', 'users', 'actions'));
    }
}
