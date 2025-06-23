<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class ActivityLog extends SpatieActivity
{
    use HasFactory;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function subject(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('subject');
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('causer_id', $userId);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query->where('subject_type', $modelType);
        
        if ($modelId) {
            $query->where('subject_id', $modelId);
        }
        
        return $query;
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // Helper methods
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y H:i');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getActionLabelAttribute()
    {
        $labels = [
            'user_login' => 'User Login',
            'user_logout' => 'User Logout',
            'user_registered' => 'User Registration',
            'article_viewed' => 'Article Viewed',
            'article_created' => 'Article Created',
            'article_updated' => 'Article Updated',
            'article_deleted' => 'Article Deleted',
            'article_published' => 'Article Published',
            'article_unpublished' => 'Article Unpublished',
            'comment_added' => 'Comment Added',
            'message_sent' => 'Message Sent',
            'chat_created' => 'Chat Created',
            'profile_updated' => 'Profile Updated',
            'user_created' => 'User Created',
            'user_updated' => 'User Updated',
            'user_deleted' => 'User Deleted',
            'user_activated' => 'User Activated',
            'user_deactivated' => 'User Deactivated',
        ];

        return $labels[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    public function getIconAttribute()
    {
        $icons = [
            'user_login' => 'fas fa-sign-in-alt',
            'user_logout' => 'fas fa-sign-out-alt',
            'user_registered' => 'fas fa-user-plus',
            'article_viewed' => 'fas fa-eye',
            'article_created' => 'fas fa-plus-circle',
            'article_updated' => 'fas fa-edit',
            'article_deleted' => 'fas fa-trash',
            'article_published' => 'fas fa-globe-americas',
            'article_unpublished' => 'fas fa-eye-slash',
            'comment_added' => 'fas fa-comment',
            'message_sent' => 'fas fa-envelope',
            'chat_created' => 'fas fa-comments',
            'profile_updated' => 'fas fa-user-edit',
            'user_created' => 'fas fa-user-plus',
            'user_updated' => 'fas fa-user-edit',
            'user_deleted' => 'fas fa-user-times',
            'user_activated' => 'fas fa-user-check',
            'user_deactivated' => 'fas fa-user-slash',
        ];

        return $icons[$this->action] ?? 'fas fa-info-circle';
    }

    // Override the log method to set action from description
    protected static function boot()
    {
        parent::boot();

        static::creating(function (SpatieActivity $activity) {
            if (isset($activity->properties['action'])) {
                $activity->action = $activity->properties['action'];
                $activity->properties = $activity->properties->except('action');
            } elseif (empty($activity->action) && !empty($activity->description)) {
                $words = explode(' ', $activity->description);
                $action = strtolower(implode('_', array_slice($words, 0, 2)));
                $activity->action = trim($action, '_');
            }
        });
    }

    // Helper method to set action and description
    public static function logActivity($description, $action = null, $properties = [])
    {
        if (!$action) {
            // Extract action from description (first few words)
            $words = explode(' ', $description);
            $action = strtolower(str_replace(' ', '_', $words[0] . ' ' . ($words[1] ?? '')));
            $action = trim($action, '_');
        }

        return activity()
            ->withProperties($properties)
            ->log($description);
    }
}
