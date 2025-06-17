<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Article $article)
    {
        return $article->is_published || $user->id === $article->author_id;
    }

    public function create(User $user)
    {
        return $user->isVeo() || $user->isAdmin();
    }

    public function update(User $user, Article $article)
    {
        return $user->id === $article->author_id || $user->isAdmin();
    }

    public function delete(User $user, Article $article)
    {
        return $user->id === $article->author_id || $user->isAdmin();
    }
}