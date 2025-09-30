<?php

echo "🔍 PHP Configuration Check\n\n";

echo "1. Command line PHP config:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   memory_limit: " . ini_get('memory_limit') . "\n";
echo "   Config file: " . (php_ini_loaded_file() ?: 'No config file') . "\n\n";

echo "2. Custom php.ini content:\n";
if (file_exists('php.ini')) {
    echo file_get_contents('php.ini');
} else {
    echo "   php.ini file not found\n";
}

echo "\n3. Testing web server config:\n";
$url = 'http://127.0.0.1:8000/test-monument-form';
$context = stream_context_create([
    'http' => [
        'timeout' => 5
    ]
]);

$response = @file_get_contents($url, false, $context);
if ($response) {
    echo "   ✅ Web server is running\n";
    
    // Check if we can access a simple route
    $testUrl = 'http://127.0.0.1:8000/api/test-config';
    echo "   Testing config endpoint...\n";
} else {
    echo "   ❌ Web server not accessible\n";
}

echo "\n4. Recommendation:\n";
echo "   The web server is likely using system PHP config instead of custom php.ini\n";
echo "   Solution: Update system php.ini or use .htaccess directives\n";
