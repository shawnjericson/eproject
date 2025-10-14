<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== USER_NOTIFICATIONS TABLE STRUCTURE ===\n";

if (Schema::hasTable('user_notifications')) {
    $columns = DB::select("DESCRIBE user_notifications");
    echo "Columns in user_notifications table:\n";
    foreach ($columns as $column) {
        echo "  {$column->Field} - {$column->Type} - {$column->Null} - {$column->Key} - {$column->Default}\n";
    }
    
    $count = DB::table('user_notifications')->count();
    echo "\nRecords: $count\n";
} else {
    echo "Table user_notifications does not exist\n";
}

echo "\n=== CHECKING FOR MISSING COLUMNS ===\n";

// Check if is_read column exists
$hasIsRead = false;
if (Schema::hasTable('user_notifications')) {
    $columns = DB::select("DESCRIBE user_notifications");
    foreach ($columns as $column) {
        if ($column->Field === 'is_read') {
            $hasIsRead = true;
            break;
        }
    }
}

echo "is_read column exists: " . ($hasIsRead ? "YES" : "NO") . "\n";

// Check if read_at column exists
$hasReadAt = false;
if (Schema::hasTable('user_notifications')) {
    $columns = DB::select("DESCRIBE user_notifications");
    foreach ($columns as $column) {
        if ($column->Field === 'read_at') {
            $hasReadAt = true;
            break;
        }
    }
}

echo "read_at column exists: " . ($hasReadAt ? "YES" : "NO") . "\n";


