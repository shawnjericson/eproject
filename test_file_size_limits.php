<?php

// Test file size limits step by step
require_once 'vendor/autoload.php';

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;

// Create Laravel app context
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing File Size Limits...\n\n";

// Check PHP config
echo "1. PHP Configuration:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   memory_limit: " . ini_get('memory_limit') . "\n\n";

// Create test files of different sizes
$testFiles = [];

// 1MB file
echo "2. Creating test files...\n";
$data1mb = str_repeat('A', 1024 * 1024); // 1MB
file_put_contents('test_1mb.jpg', $data1mb);
echo "   ✅ Created 1MB file\n";

// 2MB file  
$data2mb = str_repeat('B', 2 * 1024 * 1024); // 2MB
file_put_contents('test_2mb.jpg', $data2mb);
echo "   ✅ Created 2MB file\n";

// 3MB file
$data3mb = str_repeat('C', 3 * 1024 * 1024); // 3MB
file_put_contents('test_3mb.jpg', $data3mb);
echo "   ✅ Created 3MB file\n";

// 4MB file
$data4mb = str_repeat('D', 4 * 1024 * 1024); // 4MB
file_put_contents('test_4mb.jpg', $data4mb);
echo "   ✅ Created 4MB file\n";

try {
    $cloudinaryService = new CloudinaryService();
    
    echo "\n3. Testing Cloudinary uploads:\n";
    
    // Test each file size
    $files = [
        '1MB' => 'test_1mb.jpg',
        '2MB' => 'test_2mb.jpg', 
        '3MB' => 'test_3mb.jpg',
        '4MB' => 'test_4mb.jpg'
    ];
    
    foreach ($files as $size => $filename) {
        echo "\n   Testing {$size} file...\n";
        
        if (!file_exists($filename)) {
            echo "   ❌ File {$filename} not found\n";
            continue;
        }
        
        $fileSize = filesize($filename);
        echo "   📁 Actual size: " . number_format($fileSize / 1024 / 1024, 2) . " MB\n";
        
        // Create UploadedFile
        $uploadedFile = new UploadedFile($filename, $filename, 'image/jpeg', null, true);
        
        // Test upload
        $result = $cloudinaryService->uploadImage($uploadedFile, 'test_sizes');
        
        if ($result['success']) {
            echo "   ✅ {$size} upload SUCCESS: " . substr($result['url'], -50) . "\n";
        } else {
            echo "   ❌ {$size} upload FAILED: " . $result['error'] . "\n";
        }
    }
    
    echo "\n4. Testing batch upload with mixed sizes:\n";
    
    $uploadedFiles = [];
    foreach ($files as $size => $filename) {
        if (file_exists($filename)) {
            $uploadedFiles[] = new UploadedFile($filename, "batch_{$size}.jpg", 'image/jpeg', null, true);
        }
    }
    
    $batchResult = $cloudinaryService->uploadMultipleImages($uploadedFiles, 'test_batch');
    
    echo "   Batch result: {$batchResult['uploaded_count']}/{$batchResult['total_count']} uploaded\n";
    
    foreach ($batchResult['results'] as $index => $result) {
        $size = array_keys($files)[$index];
        echo "   ✅ {$size}: " . substr($result['url'], -50) . "\n";
    }
    
    foreach ($batchResult['errors'] as $index => $error) {
        $size = array_keys($files)[$index];
        echo "   ❌ {$size}: {$error}\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} finally {
    // Clean up
    echo "\n5. Cleaning up...\n";
    foreach ($files as $size => $filename) {
        if (file_exists($filename)) {
            unlink($filename);
            echo "   🗑️ Deleted {$filename}\n";
        }
    }
}

echo "\n✅ Test completed!\n";
