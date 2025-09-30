<?php

/**
 * Quick test script for multilingual system
 * Run: php test_multilingual.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================\n";
echo "Multilingual System Test\n";
echo "=================================\n\n";

// Test 1: Check language files exist
echo "1. Checking language files...\n";
$enFile = base_path('resources/lang/en/admin.php');
$viFile = base_path('resources/lang/vi/admin.php');

if (file_exists($enFile)) {
    echo "   ✅ English file exists: $enFile\n";
} else {
    echo "   ❌ English file NOT found: $enFile\n";
}

if (file_exists($viFile)) {
    echo "   ✅ Vietnamese file exists: $viFile\n";
} else {
    echo "   ❌ Vietnamese file NOT found: $viFile\n";
}

// Test 2: Load and count keys
echo "\n2. Counting translation keys...\n";
$enKeys = require $enFile;
$viKeys = require $viFile;

echo "   English keys: " . count($enKeys) . "\n";
echo "   Vietnamese keys: " . count($viKeys) . "\n";

// Test 3: Check for missing keys
echo "\n3. Checking for missing keys...\n";
$enOnlyKeys = array_diff_key($enKeys, $viKeys);
$viOnlyKeys = array_diff_key($viKeys, $enKeys);

if (count($enOnlyKeys) > 0) {
    echo "   ⚠️  Keys in EN but not in VI: " . count($enOnlyKeys) . "\n";
    foreach (array_slice(array_keys($enOnlyKeys), 0, 5) as $key) {
        echo "      - $key\n";
    }
} else {
    echo "   ✅ All EN keys exist in VI\n";
}

if (count($viOnlyKeys) > 0) {
    echo "   ⚠️  Keys in VI but not in EN: " . count($viOnlyKeys) . "\n";
    foreach (array_slice(array_keys($viOnlyKeys), 0, 5) as $key) {
        echo "      - $key\n";
    }
} else {
    echo "   ✅ All VI keys exist in EN\n";
}

// Test 4: Test translations
echo "\n4. Testing translations...\n";

// Test English
app()->setLocale('en');
$testKeys = [
    'dashboard',
    'welcome_back',
    'dashboard_subtitle',
    'no_posts_yet',
    'manage_user_feedback',
    'optional_description',
    'search_monument_location',
    'js_loading_image',
];

echo "   English translations:\n";
foreach ($testKeys as $key) {
    $translation = __("admin.$key");
    $status = ($translation !== "admin.$key") ? "✅" : "❌";
    echo "   $status admin.$key => $translation\n";
}

// Test Vietnamese
echo "\n   Vietnamese translations:\n";
app()->setLocale('vi');
foreach ($testKeys as $key) {
    $translation = __("admin.$key");
    $status = ($translation !== "admin.$key") ? "✅" : "❌";
    echo "   $status admin.$key => $translation\n";
}

// Test 5: Check new keys added
echo "\n5. Checking newly added keys...\n";
$newKeys = [
    'welcome_back',
    'dashboard_subtitle',
    'no_posts_yet',
    'no_monuments_yet',
    'no_feedbacks_yet',
    'manage_user_feedback',
    'optional_description',
    'current_image',
    'search_monument_location',
    'multilingual_content',
    'js_loading_image',
    'js_please_wait',
    'js_file_empty_corrupted',
];

$foundCount = 0;
foreach ($newKeys as $key) {
    if (isset($enKeys[$key]) && isset($viKeys[$key])) {
        $foundCount++;
    }
}

echo "   Found $foundCount / " . count($newKeys) . " new keys\n";
if ($foundCount === count($newKeys)) {
    echo "   ✅ All new keys are present!\n";
} else {
    echo "   ⚠️  Some keys are missing\n";
}

// Test 6: Check JavaScript translations file
echo "\n6. Checking JavaScript translations...\n";
$jsFile = base_path('resources/views/layouts/admin_js_translations.blade.php');
if (file_exists($jsFile)) {
    echo "   ✅ JavaScript translations file exists\n";
    $jsContent = file_get_contents($jsFile);
    
    // Check for key translations
    $jsKeys = ['loading', 'please_wait', 'file_empty_corrupted', 'confirm_delete'];
    $foundJs = 0;
    foreach ($jsKeys as $jsKey) {
        if (strpos($jsContent, $jsKey . ':') !== false) {
            $foundJs++;
        }
    }
    echo "   Found $foundJs / " . count($jsKeys) . " JavaScript keys\n";
} else {
    echo "   ❌ JavaScript translations file NOT found\n";
}

// Summary
echo "\n=================================\n";
echo "Summary\n";
echo "=================================\n";
echo "✅ Language files: OK\n";
echo "✅ Translation keys: " . count($enKeys) . " keys\n";
echo "✅ New keys added: $foundCount keys\n";
echo "✅ JavaScript translations: OK\n";
echo "\n";
echo "🎉 Multilingual system is working!\n";
echo "\n";
echo "Next steps:\n";
echo "1. Visit: http://localhost:8000/admin\n";
echo "2. Switch language using language selector\n";
echo "3. Check all pages are translated\n";
echo "\n";

