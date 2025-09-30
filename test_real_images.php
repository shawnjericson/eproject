<?php

// Test with real image files
require_once 'vendor/autoload.php';

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;

// Create Laravel app context
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing Real Image Upload Limits...\n\n";

// Check PHP config
echo "1. Current PHP Configuration:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   memory_limit: " . ini_get('memory_limit') . "\n\n";

// Create real JPEG images of different sizes
echo "2. Creating real JPEG test images...\n";

function createJpegImage($filename, $width, $height, $quality = 90) {
    $image = imagecreatetruecolor($width, $height);
    
    // Fill with random colors to make file larger
    for ($x = 0; $x < $width; $x += 10) {
        for ($y = 0; $y < $height; $y += 10) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($image, $x, $y, $x + 10, $y + 10, $color);
        }
    }
    
    // Add text
    $white = imagecolorallocate($image, 255, 255, 255);
    imagestring($image, 5, 50, 50, "Test Image {$width}x{$height}", $white);
    
    imagejpeg($image, $filename, $quality);
    imagedestroy($image);
    
    return filesize($filename);
}

// Create images of different sizes
$images = [
    'small' => ['test_small.jpg', 800, 600],      // ~500KB
    'medium' => ['test_medium.jpg', 1500, 1200],  // ~1.5MB  
    'large' => ['test_large.jpg', 2000, 1600],    // ~2.5MB
    'xlarge' => ['test_xlarge.jpg', 2500, 2000],  // ~4MB
];

foreach ($images as $size => $config) {
    $fileSize = createJpegImage($config[0], $config[1], $config[2]);
    echo "   ✅ Created {$size} image: " . number_format($fileSize / 1024 / 1024, 2) . " MB\n";
}

try {
    $cloudinaryService = new CloudinaryService();
    
    echo "\n3. Testing Cloudinary uploads with real images:\n";
    
    foreach ($images as $size => $config) {
        $filename = $config[0];
        echo "\n   Testing {$size} image...\n";
        
        if (!file_exists($filename)) {
            echo "   ❌ File {$filename} not found\n";
            continue;
        }
        
        $fileSize = filesize($filename);
        echo "   📁 Size: " . number_format($fileSize / 1024 / 1024, 2) . " MB\n";
        
        // Create UploadedFile
        $uploadedFile = new UploadedFile($filename, $filename, 'image/jpeg', null, true);
        
        // Test upload
        $result = $cloudinaryService->uploadImage($uploadedFile, 'test_real');
        
        if ($result['success']) {
            echo "   ✅ {$size} upload SUCCESS\n";
            echo "   🔗 URL: " . substr($result['url'], -60) . "\n";
        } else {
            echo "   ❌ {$size} upload FAILED: " . $result['error'] . "\n";
        }
    }
    
    echo "\n4. Testing form upload simulation:\n";
    
    // Test what happens when we upload through Laravel validation
    foreach ($images as $size => $config) {
        $filename = $config[0];
        if (!file_exists($filename)) continue;
        
        $fileSize = filesize($filename);
        echo "\n   Testing {$size} through Laravel validation...\n";
        echo "   📁 Size: " . number_format($fileSize / 1024 / 1024, 2) . " MB\n";
        
        // Check if file size exceeds PHP limits
        $maxSize = ini_get('upload_max_filesize');
        $maxBytes = (int)$maxSize * 1024 * 1024; // Convert to bytes
        
        if ($fileSize > $maxBytes) {
            echo "   ❌ File exceeds PHP upload_max_filesize ({$maxSize})\n";
        } else {
            echo "   ✅ File within PHP limits\n";
            
            // Test Laravel validation
            $uploadedFile = new UploadedFile($filename, $filename, 'image/jpeg', null, true);
            
            if ($uploadedFile->isValid()) {
                echo "   ✅ Laravel validation passed\n";
            } else {
                echo "   ❌ Laravel validation failed: " . $uploadedFile->getErrorMessage() . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} finally {
    // Clean up
    echo "\n5. Cleaning up...\n";
    foreach ($images as $size => $config) {
        $filename = $config[0];
        if (file_exists($filename)) {
            unlink($filename);
            echo "   🗑️ Deleted {$filename}\n";
        }
    }
}

echo "\n✅ Test completed!\n";
