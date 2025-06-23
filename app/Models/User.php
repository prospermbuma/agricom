<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'region',
        'village',
        'crops',
        'bio',
        'avatar',
        'is_active',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'crops' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Spatie log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'phone'])
            ->logOnlyDirty();
    }

    // ---------------- Relationships ----------------

    public function farmerProfile()
    {
        return $this->hasOne(FarmerProfile::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function chatParticipants()
    {
        return $this->hasMany(ChatParticipant::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function createdConversations()
    {
        return $this->hasMany(ChatConversation::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    // ---------------- Query Scopes ----------------

    public function scopeFarmers($query)
    {
        return $query->where('role', 'farmer');
    }

    public function scopeVeos($query)
    {
        return $query->where('role', 'veo');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByVillage($query, $village)
    {
        return $query->where('village', $village);
    }

    // ---------------- Helper Methods ----------------

    public function isFarmer()
    {
        return $this->role === 'farmer';
    }

    public function isVeo()
    {
        return $this->role === 'veo';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return asset('images/default-avatar.png');
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
    }

    public function getRoleLabelAttribute()
    {
        $labels = [
            'farmer' => 'Farmer',
            'veo' => 'Village Extension Officer',
            'admin' => 'Administrator',
        ];

        return $labels[$this->role] ?? ucfirst($this->role);
    }

    public function canCreateArticles()
    {
        return $this->isVeo() || $this->isAdmin();
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canViewActivityLogs()
    {
        return $this->isVeo() || $this->isAdmin();
    }
}
