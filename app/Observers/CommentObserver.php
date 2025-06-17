<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\NotificationService;

class CommentObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Comment $comment)
    {
        if ($comment->is_approved) {
            $this->notificationService->notifyNewComment($comment);
        }
    }
}