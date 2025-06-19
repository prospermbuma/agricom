<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $data = [];

        if ($user->isVEO()) {
            $data = [
                'total_articles' => Article::where('author_id', $user->id)->count(),
                'published_articles' => Article::where('author_id', $user->id)->published()->count(),
                'total_comments' => Comment::whereHas('article', function ($query) use ($user) {
                    $query->where('author_id', $user->id);
                })->count(),
                'recent_articles' => Article::where('author_id', $user->id)
                    ->with('comments')
                    ->latest()
                    ->take(5)
                    ->get(),
                'popular_articles' => Article::where('author_id', $user->id)
                    ->orderBy('views_count', 'desc')
                    ->take(5)
                    ->get(),
            ];
        } elseif ($user->isFarmer()) {
            $relevantArticles = Article::published();

            if (!empty($user->crops)) {
                $relevantArticles->where(function ($q) use ($user) {
                    $q->forCrops($user->crops)->orWhere('category', 'general');
                });
            }

            $data = [
                'relevant_articles_count' => $relevantArticles->count(),
                'my_comments_count' => Comment::where('user_id', $user->id)->count(),
                'recent_articles' => $relevantArticles->latest('published_at')->take(5)->get(),
                'urgent_articles' => Article::published()
                    ->where('is_urgent')
                    ->when(!empty($user->crops), function ($q) use ($user) {
                        $q->where(function ($query) use ($user) {
                            $query->forCrops($user->crops)->orWhere('category', 'general');
                        });
                    })
                    ->latest('published_at')
                    ->take(3)
                    ->get(),
            ];
        }

        // Common stats
        $data['recent_activity'] = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', $data);
    }

    public function stats(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_veos' => User::where('role', 'veo')->count(),
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'total_comments' => Comment::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'popular_articles' => Article::orderBy('views_count', 'desc')->take(5)->get(),
        ];

        return view('dashboard.index', $stats);
    }
}
