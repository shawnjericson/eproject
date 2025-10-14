<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE STRUCTURE VERIFICATION ===\n";

$tables = [
    'users',
    'posts', 
    'monuments',
    'gallery',
    'feedbacks',
    'contacts',
    'site_settings',
    'visitor_logs',
    'post_translations',
    'monument_translations',
    'notifications',
    'personal_access_tokens',
    'password_reset_tokens'
];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $columns = DB::select("DESCRIBE $table");
        echo "\n--- Table: $table ---\n";
        foreach ($columns as $column) {
            echo "  {$column->Field} - {$column->Type} - {$column->Null} - {$column->Key} - {$column->Default}\n";
        }
        
        $count = DB::table($table)->count();
        echo "  Records: $count\n";
    } else {
        echo "\n✗ Table $table does not exist\n";
    }
}

echo "\n=== FOREIGN KEY CONSTRAINTS ===\n";
$constraints = DB::select("
    SELECT 
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM 
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE 
        REFERENCED_TABLE_SCHEMA = DATABASE()
        AND REFERENCED_TABLE_NAME IS NOT NULL
");

foreach ($constraints as $constraint) {
    echo "{$constraint->TABLE_NAME}.{$constraint->COLUMN_NAME} -> {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}\n";
}


