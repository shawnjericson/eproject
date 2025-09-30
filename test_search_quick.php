<?php

/**
 * Quick test script for search functionality
 * Run: php test_search_quick.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Monument;
use App\Models\Post;

echo "=================================\n";
echo "Testing Search Functionality\n";
echo "=================================\n\n";

// Test 1: Get all monuments
echo "Test 1: Get all monuments\n";
echo "-------------------------\n";
$monuments = Monument::with('translations')->get();
echo "Found {$monuments->count()} monuments:\n";
foreach ($monuments as $monument) {
    echo "  - {$monument->title}\n";
    $translations = $monument->translations;
    foreach ($translations as $trans) {
        echo "    [{$trans->language}] {$trans->title}\n";
    }
}
echo "\n";

// Test 2: Search for "Angkor"
echo "Test 2: Search for 'Angkor'\n";
echo "---------------------------\n";
$searchTerm = 'Angkor';
$results = Monument::with('translations')
    ->where(function($q) use ($searchTerm) {
        $term = '%' . $searchTerm . '%';
        $q->where('title', 'like', $term)
          ->orWhere('description', 'like', $term)
          ->orWhereHas('translations', function($tq) use ($term) {
              $tq->where('title', 'like', $term)
                 ->orWhere('description', 'like', $term);
          });
    })
    ->get();
echo "Found {$results->count()} results:\n";
foreach ($results as $result) {
    echo "  - {$result->title}\n";
}
echo "\n";

// Test 3: Search for Vietnamese text "Kỳ quan"
echo "Test 3: Search for 'Kỳ quan' (Vietnamese)\n";
echo "-----------------------------------------\n";
$searchTerm = 'Kỳ quan';
$results = Monument::with('translations')
    ->where(function($q) use ($searchTerm) {
        $term = '%' . $searchTerm . '%';
        $q->where('title', 'like', $term)
          ->orWhere('description', 'like', $term)
          ->orWhereHas('translations', function($tq) use ($term) {
              $tq->where('title', 'like', $term)
                 ->orWhere('description', 'like', $term);
          });
    })
    ->get();
echo "Found {$results->count()} results:\n";
foreach ($results as $result) {
    echo "  - {$result->title}\n";
}
echo "\n";

// Test 4: Search for "East" (zone)
echo "Test 4: Filter by zone 'East'\n";
echo "-----------------------------\n";
$results = Monument::with('translations')
    ->where('zone', 'East')
    ->get();
echo "Found {$results->count()} results:\n";
foreach ($results as $result) {
    echo "  - {$result->title} (Zone: {$result->zone})\n";
}
echo "\n";

// Test 5: Combined search + filter
echo "Test 5: Search 'temple' + Zone 'East'\n";
echo "-------------------------------------\n";
$searchTerm = 'temple';
$results = Monument::with('translations')
    ->where('zone', 'East')
    ->where(function($q) use ($searchTerm) {
        $term = '%' . $searchTerm . '%';
        $q->where('title', 'like', $term)
          ->orWhere('description', 'like', $term)
          ->orWhereHas('translations', function($tq) use ($term) {
              $tq->where('title', 'like', $term)
                 ->orWhere('description', 'like', $term);
          });
    })
    ->get();
echo "Found {$results->count()} results:\n";
foreach ($results as $result) {
    echo "  - {$result->title} (Zone: {$result->zone})\n";
}
echo "\n";

// Test 6: Get all posts
echo "Test 6: Get all posts\n";
echo "--------------------\n";
$posts = Post::with('translations')->get();
echo "Found {$posts->count()} posts:\n";
foreach ($posts as $post) {
    echo "  - {$post->title}\n";
    $translations = $post->translations;
    foreach ($translations as $trans) {
        echo "    [{$trans->language}] {$trans->title}\n";
    }
}
echo "\n";

echo "=================================\n";
echo "All tests completed!\n";
echo "=================================\n";

