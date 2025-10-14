<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== FINAL DATABASE STRUCTURE VERIFICATION ===\n";

echo "\nPOSTS table columns:\n";
foreach(Schema::getColumnListing('posts') as $col) {
    echo "- $col\n";
}

echo "\nPOST_TRANSLATIONS table columns:\n";
foreach(Schema::getColumnListing('post_translations') as $col) {
    echo "- $col\n";
}


