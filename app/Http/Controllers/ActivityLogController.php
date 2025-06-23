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

        $user = $request->user();
        $query = ActivityLog::with('user')->latest();

        // Role-based filtering
        if ($user->isVeo()) {
            // VEOs can only see:
            // 1. Their own activities
            // 2. Activities from farmers in their assigned region
            $query->where(function ($q) use ($user) {
                $q->where('causer_id', $user->id) // Their own activities
                  ->orWhereHas('user', function ($userQuery) use ($user) {
                      // Activities from farmers in their region
                      $userQuery->where('role', 'farmer')
                               ->where('region_id', $user->region_id);
                  });
            });
        }
        // Admins can see all activities (no additional filtering needed)

        // Filter by user (only for admin or if VEO is filtering their own region)
        if ($request->has('user_id') && $request->user_id) {
            if ($user->isAdmin() || ($user->isVeo() && $request->user_id == $user->id)) {
                $query->where('causer_id', $request->user_id);
            }
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
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
        
        // Get users for filter dropdown (filtered by role and region)
        if ($user->isAdmin()) {
            $users = \App\Models\User::select('id', 'name', 'email', 'role')->get();
        } else {
            // VEOs can only see farmers in their region and themselves
            $users = \App\Models\User::where(function ($q) use ($user) {
                $q->where('role', 'farmer')
                  ->where('region_id', $user->region_id)
                  ->orWhere('id', $user->id);
            })->select('id', 'name', 'email', 'role')->get();
        }
        
        $actions = ActivityLog::distinct()->pluck('action');

        return view('activity-logs.index', compact('logs', 'users', 'actions'));
    }
}
