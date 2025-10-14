<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UserNotification;

echo "=== TESTING DATABASE CONNECTIVITY ===\n";

try {
    // Test User model
    $user = User::first();
    if ($user) {
        echo "✓ User found: " . $user->email . "\n";
        echo "✓ User role: " . $user->role . "\n";
        echo "✓ User status: " . $user->status . "\n";
    } else {
        echo "✗ No user found\n";
    }

    // Test UserNotification model
    $notificationCount = UserNotification::count();
    echo "✓ User notifications count: $notificationCount\n";

    // Test creating a notification
    if ($user) {
        $notification = new UserNotification();
        $notification->user_id = $user->id;
        $notification->type = 'test';
        $notification->title = 'Test Notification';
        $notification->message = 'This is a test notification';
        $notification->is_read = false;
        $notification->save();
        echo "✓ Test notification created successfully\n";
        
        // Clean up
        $notification->delete();
        echo "✓ Test notification cleaned up\n";
    }

    echo "\n=== DATABASE TEST COMPLETED SUCCESSFULLY ===\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}


