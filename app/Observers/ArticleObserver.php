<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\NotificationService;

class ArticleObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Article $article)
    {
        if ($article->is_published) {
            $this->notificationService->notifyNewArticle($article);
        }
    }

    public function updated(Article $article)
    {
        // If article was just published
        if ($article->is_published && $article->wasChanged('is_published')) {
            $this->notificationService->notifyNewArticle($article);
        }
    }
}