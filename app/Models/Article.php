<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Article extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'slug',
        'author_id',
        'category',
        'target_crops',
        'featured_image',
        'attachments',
        'is_published',
        'is_urgent',
        'published_at',
        'views_count'
    ];

    protected $casts = [
        'target_crops' => 'array',
        'attachments' => 'array',
        'is_published' => 'boolean',
        'is_urgent' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'category', 'is_published', 'is_urgent'])
            ->logOnlyDirty();
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
