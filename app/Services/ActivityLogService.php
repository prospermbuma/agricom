<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    public function log(string $action, string $description, ?User $user = null, ?Model $model = null, array $properties = [])
    {
        // Add IP address and user agent to properties
        $properties['ip_address'] = request()->ip();
        $properties['user_agent'] = request()->userAgent();

        $activity = activity()
            ->withProperties($properties)
            ->log($description);

        // Set the action if provided
        if ($action) {
            $activity->update(['action' => $action]);
        }

        return $activity;
    }

    public function getUserActivity(User $user, int $limit = 50)
    {
        return ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getSystemActivity(int $limit = 100)
    {
        return ActivityLog::with('user')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getModelActivity(Model $model, int $limit = 50)
    {
        return ActivityLog::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->with('user')
            ->latest()
            ->take($limit)
            ->get();
    }
}
