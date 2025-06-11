<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $fillable = [
        'name',
        'type',
        'crop_id',
        'participants'
    ];

    protected function casts(): array
    {
        return [
            'participants' => 'array',
        ];
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function getParticipantUsersAttribute()
    {
        return User::whereIn('id', $this->participants ?? [])->get();
    }
}
