<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING MONUMENTS DATA ===\n";
$count = DB::table('monuments')->count();
echo "Monuments count: $count\n";

if ($count > 0) {
    $ids = DB::table('monuments')->pluck('id')->toArray();
    echo "Monument IDs: " . implode(', ', $ids) . "\n";
    
    echo "\n=== SAMPLE MONUMENTS ===\n";
    $monuments = DB::table('monuments')->select('id', 'name', 'status')->limit(5)->get();
    foreach ($monuments as $monument) {
        echo "ID: {$monument->id}, Name: {$monument->name}, Status: {$monument->status}\n";
    }
} else {
    echo "No monuments found in database!\n";
}

echo "\n=== CHECKING MONUMENT_TRANSLATIONS DATA ===\n";
$transCount = DB::table('monument_translations')->count();
echo "Monument translations count: $transCount\n";


