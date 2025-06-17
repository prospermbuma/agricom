<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function create(User $user, string $type, string $title, string $message, array $data = [])
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function notifyNewArticle($article)
    {
        // Notify farmers who have matching crops
        $targetCrops = $article->target_crops ?? [];
        
        if (!empty($targetCrops)) {
            $farmers = User::where('role', 'farmer')
                ->where('is_active', true)
                ->get()
                ->filter(function ($farmer) use ($targetCrops) {
                    return !empty(array_intersect($farmer->crops ?? [], $targetCrops));
                });

            foreach ($farmers as $farmer) {
                $this->create(
                    $farmer,
                    'new_article',
                    'New Article Published',
                    "A new article '{$article->title}' has been published that might interest you.",
                    ['article_id' => $article->id]
                );
            }
        }

        // Notify all users for urgent articles
        if ($article->priority === 'urgent') {
            $users = User::where('role', 'farmer')
                ->where('is_active', true)
                ->get();

            foreach ($users as $user) {
                $this->create(
                    $user,
                    'urgent_article',
                    'Urgent Article Published',
                    "An urgent article '{$article->title}' has been published.",
                    ['article_id' => $article->id]
                );
            }
        }
    }

    public function notifyNewComment($comment)
    {
        $article = $comment->article;
        $author = $article->author;

        // Notify article author
        if ($author->id !== $comment->user_id) {
            $this->create(
                $author,
                'new_comment',
                'New Comment on Your Article',
                "Someone commented on your article '{$article->title}'.",
                [
                    'article_id' => $article->id,
                    'comment_id' => $comment->id,
                ]
            );
        }

        // Notify parent comment author if it's a reply
        if ($comment->parent_id) {
            $parentComment = $comment->parent;
            if ($parentComment->user_id !== $comment->user_id && $parentComment->user_id !== $author->id) {
                $this->create(
                    $parentComment->user,
                    'comment_reply',
                    'Someone Replied to Your Comment',
                    "Someone replied to your comment on '{$article->title}'.",
                    [
                        'article_id' => $article->id,
                        'comment_id' => $comment->id,
                    ]
                );
            }
        }
    }

    public function markAsRead(User $user, int $notificationId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $user->id)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function getUserNotifications(User $user, bool $unreadOnly = false, int $limit = 20)
    {
        $query = Notification::where('user_id', $user->id);

        if ($unreadOnly) {
            $query->unread();
        }

        return $query->latest()->take($limit)->get();
    }
}
