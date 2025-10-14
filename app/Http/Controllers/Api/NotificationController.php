<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get a specific notification
     */
    public function show($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        return response()->json([
            'notification' => $notification,
        ]);
    }

    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $notifications = UserNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $unreadCount = NotificationService::getUnreadCount($user);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'has_more' => $notifications->count() === $limit,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        
        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        $count = $user->notifications()->unread()->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} notifications as read",
            'count' => $count,
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = NotificationService::getUnreadCount($user);

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    public function feedbackUnreadCount(Request $request)
    {
        // Simple count - just return a test number for now
        return response()->json([
            'unread_count' => 5, // Test number
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        
        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }
}