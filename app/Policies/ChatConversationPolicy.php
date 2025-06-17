<?php

namespace App\Policies;

use App\Models\ChatConversation;
use App\Models\User;

class ChatConversationPolicy
{
    public function view(User $user, ChatConversation $conversation)
    {
        return $conversation->participants()->where('user_id', $user->id)->exists();
    }

    public function participate(User $user, ChatConversation $conversation)
    {
        return $conversation->participants()->where('user_id', $user->id)->exists();
    }
}