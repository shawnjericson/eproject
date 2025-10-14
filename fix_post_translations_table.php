<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING post_translations TABLE STRUCTURE ===\n";

try {
    // Drop and recreate post_translations table
    Schema::dropIfExists('post_translations');
    
    Schema::create('post_translations', function ($table) {
        $table->id();
        $table->unsignedBigInteger('post_id');
        $table->string('language', 5); // Changed from 'locale' to 'language'
        $table->string('title');
        $table->longText('content')->nullable();
        $table->timestamps();
        
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        $table->unique(['post_id', 'language']);
    });
    
    echo "✓ post_translations table recreated successfully\n";
    
    // Check the new structure
    echo "\n=== NEW post_translations STRUCTURE ===\n";
    $columns = Schema::getColumnListing('post_translations');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}


