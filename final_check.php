<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FINAL DATABASE STATUS ===\n";
echo "Monuments: " . DB::table('monuments')->count() . "\n";
echo "Monument Translations: " . DB::table('monument_translations')->count() . "\n";
echo "Post Translations: " . DB::table('post_translations')->count() . "\n";
echo "Users: " . DB::table('users')->count() . "\n";
echo "Posts: " . DB::table('posts')->count() . "\n";
echo "Feedbacks: " . DB::table('feedbacks')->count() . "\n";

echo "\n=== SAMPLE MONUMENT TRANSLATIONS ===\n";
$translations = DB::table('monument_translations')
    ->join('monuments', 'monument_translations.monument_id', '=', 'monuments.id')
    ->select('monuments.name', 'monument_translations.language', 'monument_translations.title')
    ->limit(5)
    ->get();

foreach ($translations as $translation) {
    echo "Monument: {$translation->name}, Lang: {$translation->language}, Title: {$translation->title}\n";
}


