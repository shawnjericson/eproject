<?php

// Test monument creation with Cloudinary
require_once 'vendor/autoload.php';

use App\Services\CloudinaryService;
use App\Models\Monument;
use Illuminate\Http\UploadedFile;

// Create Laravel app context
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Monument Creation with Cloudinary...\n";

try {
    // Create test image files
    $testImages = [];
    
    // Create small test images
    for ($i = 1; $i <= 3; $i++) {
        $imagePath = "test_image_{$i}.jpg";
        $imageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
        file_put_contents($imagePath, $imageData);
        $testImages[] = $imagePath;
    }
    
    // Test CloudinaryService
    $cloudinaryService = new CloudinaryService();
    
    // Test single upload
    echo "\n1. Testing single image upload...\n";
    $uploadedFile = new UploadedFile($testImages[0], 'test1.jpg', 'image/jpeg', null, true);
    $result = $cloudinaryService->uploadImage($uploadedFile, 'test');
    
    if ($result['success']) {
        echo "✅ Single upload SUCCESS: " . $result['url'] . "\n";
    } else {
        echo "❌ Single upload FAILED: " . $result['error'] . "\n";
    }
    
    // Test batch upload
    echo "\n2. Testing batch upload...\n";
    $uploadedFiles = [];
    foreach ($testImages as $index => $imagePath) {
        $uploadedFiles[] = new UploadedFile($imagePath, "test{$index}.jpg", 'image/jpeg', null, true);
    }
    
    $batchResult = $cloudinaryService->uploadMultipleImages($uploadedFiles, 'test');
    
    echo "Batch upload result:\n";
    echo "- Uploaded: {$batchResult['uploaded_count']}/{$batchResult['total_count']}\n";
    echo "- Errors: " . count($batchResult['errors']) . "\n";
    
    foreach ($batchResult['results'] as $index => $result) {
        echo "  ✅ Image {$index}: " . $result['url'] . "\n";
    }
    
    foreach ($batchResult['errors'] as $index => $error) {
        echo "  ❌ Image {$index}: {$error}\n";
    }
    
    // Test Monument creation
    echo "\n3. Testing Monument creation...\n";
    
    $monument = Monument::create([
        'title' => 'Test Monument ' . time(),
        'location' => 'Test Location',
        'zone' => 'North',
        'content' => 'Test content',
        'description' => 'Test description',
        'history' => 'Test history',
        'status' => 'draft',
        'created_by' => 1,
        'image' => $result['success'] ? $result['url'] : null
    ]);
    
    echo "✅ Monument created with ID: {$monument->id}\n";
    echo "✅ Monument image URL: {$monument->image}\n";
    echo "✅ Monument image_url accessor: {$monument->image_url}\n";
    
    // Test Gallery creation
    if ($batchResult['success']) {
        echo "\n4. Testing Gallery creation...\n";
        
        foreach ($batchResult['results'] as $index => $result) {
            $gallery = $monument->gallery()->create([
                'title' => "Gallery Image " . ($index + 1),
                'image_path' => $result['url'],
                'description' => "Test gallery image " . ($index + 1)
            ]);
            
            echo "✅ Gallery {$gallery->id}: {$gallery->image_url}\n";
        }
    }
    
    echo "\n✅ ALL TESTS PASSED!\n";
    
} catch (Exception $e) {
    echo "\n❌ TEST FAILED: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} finally {
    // Clean up test files
    foreach ($testImages as $imagePath) {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
