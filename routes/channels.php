<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('online-users', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar_url' => $user->avatar_url,
        'role' => $user->role,
        'village' => $user->village,
    ];
}); 