<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'joined_at',
        'left_at',
        'is_admin',
        'is_muted'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_muted' => 'boolean',
    ];

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('left_at');
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeMuted($query)
    {
        return $query->where('is_muted', true);
    }

    // Helper methods
    public function isActive(): bool
    {
        return is_null($this->left_at);
    }

    public function leave(): bool
    {
        return $this->update(['left_at' => now()]);
    }

    public function rejoin(): bool
    {
        return $this->update(['left_at' => null]);
    }

    public function toggleMute(): bool
    {
        return $this->update(['is_muted' => !$this->is_muted]);
    }

    public function makeAdmin(): bool
    {
        return $this->update(['is_admin' => true]);
    }

    public function removeAdmin(): bool
    {
        return $this->update(['is_admin' => false]);
    }
}
