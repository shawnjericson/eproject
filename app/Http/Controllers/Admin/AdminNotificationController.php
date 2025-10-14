<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    // Đánh dấu 1 thông báo đã đọc
    public function markRead($id)
    {
        $user = auth()->user();

        // Tìm thông báo và đánh dấu đã đọc
        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    // Đánh dấu tất cả thông báo đã đọc
    public function markAllAsRead()
    {
        $user = auth()->user();
        
        $count = UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    // Đếm số lượng thông báo chưa đọc
    public function unreadCount()
    {
        $user = auth()->user();

        $count = UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
