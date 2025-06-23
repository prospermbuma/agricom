<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUserNotifications($user, false, 50);

        return view('notifications.index', compact('notifications'));
    }

    public function unread(Request $request)
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUserNotifications($user, true, 20);

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $user = $request->user();

        if ($notification->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $this->notificationService->markAllAsRead($user);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, Notification $notification)
    {
        $user = $request->user();

        if ($notification->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function clearAll(Request $request)
    {
        $user = $request->user();
        
        Notification::where('user_id', $user->id)->delete();

        return response()->json(['success' => true]);
    }
}