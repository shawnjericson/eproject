<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING monument_translations TABLE STRUCTURE ===\n";

try {
    // Drop the table and recreate with correct structure
    Schema::dropIfExists('monument_translations');
    
    Schema::create('monument_translations', function ($table) {
        $table->id();
        $table->unsignedBigInteger('monument_id');
        $table->string('language', 5); // Changed from 'locale' to 'language'
        $table->string('title'); // Changed from 'name' to 'title'
        $table->text('description')->nullable();
        $table->text('history')->nullable(); // Added missing column
        $table->longText('content')->nullable();
        $table->string('location')->nullable(); // Added missing column
        $table->timestamps();
        
        $table->foreign('monument_id')->references('id')->on('monuments')->onDelete('cascade');
        $table->unique(['monument_id', 'language']);
    });
    
    echo "✓ monument_translations table recreated successfully\n";
    
    // Check the new structure
    echo "\n=== NEW TABLE STRUCTURE ===\n";
    $columns = Schema::getColumnListing('monument_translations');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}


