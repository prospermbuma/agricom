<?php

namespace App\Http\Controllers;

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
        $notifications = $this->notificationService->getUserNotifications(
            $request->user(),
            false,
            $request->get('limit', 20)
        );

        return response()->json($notifications);
    }

    public function unread(Request $request)
    {
        $notifications = $this->notificationService->getUserNotifications(
            $request->user(),
            true,
            $request->get('limit', 20)
        );

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $result = $this->notificationService->markAsRead($request->user(), $notificationId);

        if ($result) {
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function markAllAsRead(Request $request)
    {
        $this->notificationService->markAllAsRead($request->user());

        return response()->json(['message' => 'All notifications marked as read']);
    }
}