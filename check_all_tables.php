<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING ALL TABLE STRUCTURES ===\n";

$tables = ['users', 'posts', 'monuments', 'post_translations', 'monument_translations', 'contacts', 'feedbacks', 'gallery', 'site_settings', 'user_notifications'];

foreach ($tables as $table) {
    echo "\n--- $table ---\n";
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        foreach ($columns as $column) {
            echo "- $column\n";
        }
    } else {
        echo "✗ Table does not exist\n";
    }
}


