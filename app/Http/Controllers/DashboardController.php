<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recentArticles = Article::published()
            ->latest()
            ->limit(5)
            ->get();

        $urgentArticles = Article::published()
            ->urgent()
            ->latest()
            ->limit(3)
            ->get();

        $stats = [
            'total_farmers' => User::farmers()->count(),
            'total_veos' => User::veos()->count(),
            'total_articles' => Article::published()->count(),
            'my_conversations' => $user->chatParticipants()->count(),
        ];

        return view('dashboard', compact('recentArticles', 'urgentArticles', 'stats'));
    }
}
