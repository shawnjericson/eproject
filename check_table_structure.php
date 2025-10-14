<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CURRENT monument_translations TABLE STRUCTURE ===\n";
$columns = Schema::getColumnListing('monument_translations');
foreach ($columns as $column) {
    echo "- $column\n";
}

echo "\n=== CHECKING IF LANGUAGE COLUMN EXISTS ===\n";
if (in_array('language', $columns)) {
    echo "✓ language column exists\n";
} else {
    echo "✗ language column MISSING\n";
}

echo "\n=== CHECKING OTHER COLUMNS ===\n";
$expectedColumns = ['id', 'monument_id', 'language', 'title', 'description', 'history', 'content', 'location', 'created_at', 'updated_at'];
foreach ($expectedColumns as $col) {
    if (in_array($col, $columns)) {
        echo "✓ $col\n";
    } else {
        echo "✗ $col MISSING\n";
    }
}


