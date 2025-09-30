<?php

// Simple test upload to Cloudinary without Laravel
$cloudName = 'dfnlmwmqj';
$apiKey = '381342661923928';
$apiSecret = 'B8EtSXCVcPgCI5r_DaiuGFijNSo';

// Create a simple test image
$testImagePath = 'test_image.jpg';
$imageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
file_put_contents($testImagePath, $imageData);

echo "Testing Cloudinary upload with simple HTTP...\n";

try {
    $timestamp = time();
    $publicId = 'test/' . $timestamp . '_simple_test';
    
    // Create signature - Cloudinary format
    $params = [
        'public_id' => $publicId,
        'timestamp' => $timestamp
    ];

    ksort($params);
    $signatureString = '';
    foreach ($params as $key => $value) {
        $signatureString .= $key . '=' . $value . '&';
    }
    $signatureString = rtrim($signatureString, '&'); // Remove trailing &
    $signatureString .= $apiSecret; // Append secret without &
    $signature = sha1($signatureString);
    
    echo "Signature: $signature\n";
    
    // Prepare POST data
    $postData = [
        'file' => new CURLFile($testImagePath, 'image/jpeg', 'test.jpg'),
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'public_id' => $publicId,
        'signature' => $signature
    ];
    
    // Upload using cURL with HTTP (not HTTPS)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    echo "cURL Error: $error\n";
    echo "Response: $response\n";
    
    if ($error) {
        throw new Exception("cURL Error: " . $error);
    }
    
    if ($httpCode !== 200) {
        throw new Exception("HTTP Error: " . $httpCode . " - " . $response);
    }
    
    $result = json_decode($response, true);
    
    if (!$result || !isset($result['secure_url'])) {
        throw new Exception("Invalid response: " . $response);
    }
    
    echo "SUCCESS!\n";
    echo "URL: " . $result['secure_url'] . "\n";
    echo "Public ID: " . $result['public_id'] . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Clean up
if (file_exists($testImagePath)) {
    unlink($testImagePath);
}
