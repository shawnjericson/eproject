<?php

require_once 'vendor/autoload.php';

use Cloudinary\Cloudinary;

// Disable SSL verification for Windows
putenv('CLOUDINARY_SECURE=false');

// Test Cloudinary connection
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dfnlmwmqj',
        'api_key' => '381342661923928',
        'api_secret' => 'B8EtSXCVcPgCI5r_DaiuGFijNSo',
    ],
]);

try {
    echo "Testing Cloudinary connection...\n";
    $result = $cloudinary->adminApi()->ping();
    echo "Connection successful!\n";
    print_r($result);
    
    // Test upload with a simple image
    echo "\nTesting upload...\n";
    $uploadResult = $cloudinary->uploadApi()->upload(
        'https://via.placeholder.com/300x200.jpg',
        [
            'folder' => 'test',
            'public_id' => 'test_image_' . time()
        ]
    );
    
    echo "Upload successful!\n";
    echo "URL: " . $uploadResult['secure_url'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
