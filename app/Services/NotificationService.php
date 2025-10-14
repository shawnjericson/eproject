<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public static function create(User $user, string $type, string $title, string $message, array $data = []): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create post rejected notification.
     */
    public static function postRejected(User $user, $post, string $reason, User $admin): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.post_rejected_title', [], 'vi');
        $titleEn = __('admin.notification.post_rejected_title', [], 'en');
        $messageVi = __('admin.notification.post_rejected_message', [
            'title' => $post->title,
            'rejected_by' => $admin->name,
        ], 'vi');
        $messageEn = __('admin.notification.post_rejected_message', [
            'title' => $post->title,
            'rejected_by' => $admin->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($user, 'post_rejected', $title, $message, [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'reason' => $reason,
            'admin_name' => $admin->name,
            'admin_id' => $admin->id,
        ]);
    }

    /**
     * Create post deleted notification.
     */
    public static function postDeleted(User $user, $post, string $reason, User $admin): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.post_deleted_title', [], 'vi');
        $titleEn = __('admin.notification.post_deleted_title', [], 'en');
        $messageVi = __('admin.notification.post_deleted_message', [
            'title' => $post->title,
            'deleted_by' => $admin->name,
        ], 'vi');
        $messageEn = __('admin.notification.post_deleted_message', [
            'title' => $post->title,
            'deleted_by' => $admin->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($user, 'post_deleted', $title, $message, [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'reason' => $reason,
            'admin_name' => $admin->name,
            'admin_id' => $admin->id,
        ]);
    }

    /**
     * Create monument rejected notification.
     */
    public static function monumentRejected(User $user, $monument, string $reason, User $admin): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.monument_rejected_title', [], 'vi');
        $titleEn = __('admin.notification.monument_rejected_title', [], 'en');
        $messageVi = __('admin.notification.monument_rejected_message', [
            'name' => $monument->title,
            'rejected_by' => $admin->name,
        ], 'vi');
        $messageEn = __('admin.notification.monument_rejected_message', [
            'name' => $monument->title,
            'rejected_by' => $admin->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($user, 'monument_rejected', $title, $message, [
            'monument_id' => $monument->id,
            'monument_title' => $monument->title,
            'reason' => $reason,
            'admin_name' => $admin->name,
            'admin_id' => $admin->id,
        ]);
    }

    /**
     * Create monument deleted notification.
     */
    public static function monumentDeleted(User $user, $monument, string $reason, User $admin): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.monument_deleted_title', [], 'vi');
        $titleEn = __('admin.notification.monument_deleted_title', [], 'en');
        $messageVi = __('admin.notification.monument_deleted_message', [
            'name' => $monument->title,
            'deleted_by' => $admin->name,
        ], 'vi');
        $messageEn = __('admin.notification.monument_deleted_message', [
            'name' => $monument->title,
            'deleted_by' => $admin->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($user, 'monument_deleted', $title, $message, [
            'monument_id' => $monument->id,
            'monument_title' => $monument->title,
            'reason' => $reason,
            'admin_name' => $admin->name,
            'admin_id' => $admin->id,
        ]);
    }

    /**
     * Create monument created by moderator notification.
     */
    public static function monumentCreatedByModerator(User $admin, $monument, User $moderator): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.monument_created_by_moderator_title', [], 'vi');
        $titleEn = __('admin.notification.monument_created_by_moderator_title', [], 'en');
        $messageVi = __('admin.notification.monument_created_by_moderator_message', [
            'name' => $monument->title,
            'created_by' => $moderator->name,
        ], 'vi');
        $messageEn = __('admin.notification.monument_created_by_moderator_message', [
            'name' => $monument->title,
            'created_by' => $moderator->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($admin, 'monument_created_by_moderator', $title, $message, [
            'monument_id' => $monument->id,
            'monument_title' => $monument->title,
            'moderator_name' => $moderator->name,
            'moderator_id' => $moderator->id,
            'url' => route('admin.monuments.show', $monument),
        ]);
    }

    /**
     * Create post created by moderator notification.
     */
    public static function postCreatedByModerator(User $admin, $post, User $moderator): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('admin.notification.post_created_by_moderator_title', [], 'vi');
        $titleEn = __('admin.notification.post_created_by_moderator_title', [], 'en');
        $messageVi = __('admin.notification.post_created_by_moderator_message', [
            'title' => $post->title,
            'created_by' => $moderator->name,
        ], 'vi');
        $messageEn = __('admin.notification.post_created_by_moderator_message', [
            'title' => $post->title,
            'created_by' => $moderator->name,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($admin, 'post_created_by_moderator', $title, $message, [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'moderator_name' => $moderator->name,
            'moderator_id' => $moderator->id,
            'url' => route('admin.posts.show', $post),
        ]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsRead(User $user): int
    {
        return UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread count for a user.
     */
    public static function getUnreadCount(User $user): int
    {
        return UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }


    /**
     * Create user registration notification for admin.
     */
    public static function userRegistration(User $admin, User $newUser): UserNotification
    {
        // Create notification with both languages
        $titleVi = __('notification.user_registration_title', [], 'vi');
        $titleEn = __('notification.user_registration_title', [], 'en');
        $messageVi = __('notification.user_registration_message', [
            'user_name' => $newUser->name,
            'user_email' => $newUser->email,
        ], 'vi');
        $messageEn = __('notification.user_registration_message', [
            'user_name' => $newUser->name,
            'user_email' => $newUser->email,
        ], 'en');

        $title = "{$titleVi} / {$titleEn}";
        $message = "{$messageVi}\n\n{$messageEn}";

        return self::create($admin, 'user_registration', $title, $message, [
            'user_id' => $newUser->id,
            'user_name' => $newUser->name,
            'user_email' => $newUser->email,
            'user_role' => $newUser->role,
            'user_status' => $newUser->status,
            'url' => route('admin.users.show', $newUser),
        ]);
    }

    /**
     * Notify all admins about new user registration.
     */
    public static function notifyAdminsAboutNewUser(User $newUser): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::userRegistration($admin, $newUser);
        }
    }

    /**
     * Get recent notifications for a user.
     */
    public static function getRecent(User $user, int $limit = 10)
    {
        return UserNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

}
