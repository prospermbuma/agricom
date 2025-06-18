<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware(['auth', 'activity.logger'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Article routes
    Route::resource('articles', ArticleController::class);
    Route::post('/articles/{article}/comments', [ArticleController::class, 'storeComment'])->name('articles.comments.store');

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'store'])->name('chat.messages.store');
    Route::post('/chat/conversations', [ChatController::class, 'createConversation'])->name('chat.conversations.store');

    // Activity Logs (VEO only)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // API Routes for AJAX
    Route::get('/api/villages/{region}', function ($regionId) {
        return \App\Models\Village::where('region_id', $regionId)->get();
    });
});
