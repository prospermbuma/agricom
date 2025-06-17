<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(string $action, string $description, User $user = null, Model $model = null, array $properties = [])
    {
        $logData = [
            'action' => $action,
            'description' => $description,
            'user_id' => $user ? $user->id : auth()->id(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($model) {
            $logData['model_type'] = get_class($model);
            $logData['model_id'] = $model->id;
        }

        return ActivityLog::create($logData);
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