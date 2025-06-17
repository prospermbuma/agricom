<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Article routes
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::post('/', [ArticleController::class, 'store'])->middleware('role:veo,admin');
        Route::get('/my-articles', [ArticleController::class, 'myArticles'])->middleware('role:veo,admin');
        Route::get('/{article}', [ArticleController::class, 'show']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);

        // Article comments
        Route::post('/{article}/comments', [CommentController::class, 'store']);
    });

    // Comment routes
    Route::prefix('comments')->group(function () {
        Route::put('/{comment}', [CommentController::class, 'update']);
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
    });

    // Chat routes
    Route::prefix('chats')->group(function () {
        Route::get('/', [ChatController::class, 'index']);
        Route::post('/', [ChatController::class, 'store']);
        Route::get('/users', [ChatController::class, 'getUsers']);
        Route::get('/{chat}', [ChatController::class, 'show']);
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    });

    // Activity log routes (admin only)
    Route::prefix('activity-logs')->middleware('role:admin')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index']);
        Route::get('/user/{user}', [ActivityLogController::class, 'userActivity']);
        Route::get('/export', [ActivityLogController::class, 'export']);
    });

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::put('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);
        Route::get('/articles/pending', [AdminController::class, 'pendingArticles']);
        Route::get('/comments/pending', [AdminController::class, 'pendingComments']);
        Route::put('/comments/{comment}/approve', [AdminController::class, 'approveComment']);
    });

    // Data routes
    Route::prefix('data')->group(function () {
        Route::get('/regions', [DataController::class, 'regions']);
        Route::get('/villages/{region}', [DataController::class, 'villages']);
        Route::get('/crop-types', [DataController::class, 'cropTypes']);
    });
});
