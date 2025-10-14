<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== FINAL DATABASE STRUCTURE VERIFICATION ===\n";

$tables = ['users', 'posts', 'monuments', 'gallery', 'monument_translations', 'post_translations', 'site_settings', 'contacts', 'feedbacks', 'visitor_logs'];

foreach($tables as $table) {
    echo "\n" . strtoupper($table) . " table columns:\n";
    foreach(Schema::getColumnListing($table) as $col) {
        echo "- $col\n";
    }
}


