<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'created_by',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latest();
    }

    public function activeParticipants()
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id')->active();
    }

    // Scopes
    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId)->whereNull('left_at');
        });
    }

    // Helper methods
    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    public function hasParticipant($userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    public function addParticipant($userId, $isAdmin = false): ChatParticipant
    {
        return $this->participants()->create([
            'user_id' => $userId,
            'joined_at' => now(),
            'is_admin' => $isAdmin,
        ]);
    }

    public function removeParticipant($userId): bool
    {
        return $this->participants()
            ->where('user_id', $userId)
            ->update(['left_at' => now()]);
    }

    public function updateLastMessage(): bool
    {
        return $this->update(['last_message_at' => now()]);
    }

    public function getUnreadCount($userId): int
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
