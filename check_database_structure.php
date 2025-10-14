<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== DATABASE STRUCTURE VERIFICATION ===\n";

echo "\nUsers table columns:\n";
foreach(Schema::getColumnListing('users') as $col) {
    echo "- $col\n";
}

echo "\nMonuments table columns:\n";
foreach(Schema::getColumnListing('monuments') as $col) {
    echo "- $col\n";
}

echo "\nSite Settings table columns:\n";
foreach(Schema::getColumnListing('site_settings') as $col) {
    echo "- $col\n";
}

echo "\nGallery table columns:\n";
foreach(Schema::getColumnListing('gallery') as $col) {
    echo "- $col\n";
}

echo "\nMonument Translations table columns:\n";
foreach(Schema::getColumnListing('monument_translations') as $col) {
    echo "- $col\n";
}

echo "\nPost Translations table columns:\n";
foreach(Schema::getColumnListing('post_translations') as $col) {
    echo "- $col\n";
}

echo "\nContacts table columns:\n";
foreach(Schema::getColumnListing('contacts') as $col) {
    echo "- $col\n";
}

echo "\nFeedbacks table columns:\n";
foreach(Schema::getColumnListing('feedbacks') as $col) {
    echo "- $col\n";
}


