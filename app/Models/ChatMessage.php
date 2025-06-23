<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'type',
        'file_path',
        'file_name',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
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
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeText($query)
    {
        return $query->where('type', 'text');
    }

    public function scopeFile($query)
    {
        return $query->where('type', 'file');
    }

    public function scopeImage($query)
    {
        return $query->where('type', 'image');
    }

    // Helper methods
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['is_read' => false]);
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function getFileUrl(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getFileSize(): ?int
    {
        if ($this->file_path && file_exists(storage_path('app/public/' . $this->file_path))) {
            return filesize(storage_path('app/public/' . $this->file_path));
        }
        return null;
    }

    public function getFileExtension(): ?string
    {
        if ($this->file_name) {
            return pathinfo($this->file_name, PATHINFO_EXTENSION);
        }
        return null;
    }
}
