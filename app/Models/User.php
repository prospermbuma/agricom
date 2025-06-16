<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'avatar',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean'
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'phone'])
            ->logOnlyDirty();
    }

    // Relationships
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

    // Scopes
    public function scopeFarmers($query)
    {
        return $query->where('role', 'farmer');
    }

    public function scopeVeos($query)
    {
        return $query->where('role', 'veo');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
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
}
