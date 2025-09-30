<?php

/**
 * Test script for ownership checking
 * Run: php test_ownership.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Post;
use App\Models\Monument;

echo "=================================\n";
echo "Testing Ownership System\n";
echo "=================================\n\n";

// Get users
echo "Step 1: Get users\n";
echo "-----------------\n";
$admin = User::where('role', 'admin')->first();
$moderators = User::where('role', 'moderator')->get();

if (!$admin) {
    echo "❌ No admin found!\n";
    exit(1);
}

echo "✅ Admin found: {$admin->name} (ID: {$admin->id})\n";

if ($moderators->count() < 2) {
    echo "⚠️  Need at least 2 moderators for testing. Found: {$moderators->count()}\n";
    echo "Creating test moderators...\n";
    
    // Create moderator 1
    $mod1 = User::create([
        'name' => 'Test Moderator 1',
        'email' => 'mod1@test.com',
        'password' => bcrypt('password'),
        'role' => 'moderator',
        'status' => 'active',
    ]);
    
    // Create moderator 2
    $mod2 = User::create([
        'name' => 'Test Moderator 2',
        'email' => 'mod2@test.com',
        'password' => bcrypt('password'),
        'role' => 'moderator',
        'status' => 'active',
    ]);
    
    echo "✅ Created 2 test moderators\n";
} else {
    $mod1 = $moderators[0];
    $mod2 = $moderators[1];
    echo "✅ Moderator 1: {$mod1->name} (ID: {$mod1->id})\n";
    echo "✅ Moderator 2: {$mod2->name} (ID: {$mod2->id})\n";
}

echo "\n";

// Test Posts
echo "Step 2: Test Posts Ownership\n";
echo "-----------------------------\n";

// Get posts
$postByMod1 = Post::where('created_by', $mod1->id)->first();
$postByMod2 = Post::where('created_by', $mod2->id)->first();
$postByAdmin = Post::where('created_by', $admin->id)->first();

if (!$postByMod1) {
    echo "Creating test post by Moderator 1...\n";
    $postByMod1 = Post::create([
        'title' => 'Test Post by Mod 1',
        'content' => 'Content',
        'status' => 'draft',
        'created_by' => $mod1->id,
    ]);
}

if (!$postByMod2) {
    echo "Creating test post by Moderator 2...\n";
    $postByMod2 = Post::create([
        'title' => 'Test Post by Mod 2',
        'content' => 'Content',
        'status' => 'draft',
        'created_by' => $mod2->id,
    ]);
}

echo "Posts:\n";
echo "  - Post #{$postByMod1->id} by {$mod1->name}\n";
echo "  - Post #{$postByMod2->id} by {$mod2->name}\n";
if ($postByAdmin) {
    echo "  - Post #{$postByAdmin->id} by {$admin->name}\n";
}
echo "\n";

// Test Monuments
echo "Step 3: Test Monuments Ownership\n";
echo "---------------------------------\n";

$monumentByMod1 = Monument::where('created_by', $mod1->id)->first();
$monumentByMod2 = Monument::where('created_by', $mod2->id)->first();

if (!$monumentByMod1) {
    echo "Creating test monument by Moderator 1...\n";
    $monumentByMod1 = Monument::create([
        'title' => 'Test Monument by Mod 1',
        'description' => 'Description',
        'zone' => 'East',
        'status' => 'draft',
        'created_by' => $mod1->id,
    ]);
}

if (!$monumentByMod2) {
    echo "Creating test monument by Moderator 2...\n";
    $monumentByMod2 = Monument::create([
        'title' => 'Test Monument by Mod 2',
        'description' => 'Description',
        'zone' => 'West',
        'status' => 'draft',
        'created_by' => $mod2->id,
    ]);
}

echo "Monuments:\n";
echo "  - Monument #{$monumentByMod1->id} by {$mod1->name}\n";
echo "  - Monument #{$monumentByMod2->id} by {$mod2->name}\n";
echo "\n";

// Summary
echo "=================================\n";
echo "Test Data Summary\n";
echo "=================================\n\n";

echo "Users:\n";
echo "  - Admin: {$admin->name} (ID: {$admin->id})\n";
echo "  - Moderator 1: {$mod1->name} (ID: {$mod1->id})\n";
echo "  - Moderator 2: {$mod2->name} (ID: {$mod2->id})\n";
echo "\n";

echo "Posts:\n";
echo "  - Post #{$postByMod1->id}: '{$postByMod1->title}' by Moderator 1\n";
echo "  - Post #{$postByMod2->id}: '{$postByMod2->title}' by Moderator 2\n";
echo "\n";

echo "Monuments:\n";
echo "  - Monument #{$monumentByMod1->id}: '{$monumentByMod1->title}' by Moderator 1\n";
echo "  - Monument #{$monumentByMod2->id}: '{$monumentByMod2->title}' by Moderator 2\n";
echo "\n";

echo "=================================\n";
echo "Test Scenarios\n";
echo "=================================\n\n";

echo "✅ Moderator 1 CAN edit Post #{$postByMod1->id} (their own)\n";
echo "❌ Moderator 1 CANNOT edit Post #{$postByMod2->id} (belongs to Moderator 2)\n";
echo "✅ Admin CAN edit any post\n";
echo "\n";

echo "✅ Moderator 1 CAN edit Monument #{$monumentByMod1->id} (their own)\n";
echo "❌ Moderator 1 CANNOT edit Monument #{$monumentByMod2->id} (belongs to Moderator 2)\n";
echo "✅ Admin CAN edit any monument\n";
echo "\n";

echo "=================================\n";
echo "How to Test\n";
echo "=================================\n\n";

echo "1. Login as Moderator 1 (mod1@test.com / password)\n";
echo "   - Try to edit: /admin/posts/{$postByMod1->id}/edit ✅ Should work\n";
echo "   - Try to edit: /admin/posts/{$postByMod2->id}/edit ❌ Should get 403\n";
echo "\n";

echo "2. Login as Moderator 2 (mod2@test.com / password)\n";
echo "   - Try to edit: /admin/posts/{$postByMod2->id}/edit ✅ Should work\n";
echo "   - Try to edit: /admin/posts/{$postByMod1->id}/edit ❌ Should get 403\n";
echo "\n";

echo "3. Login as Admin\n";
echo "   - Try to edit: /admin/posts/{$postByMod1->id}/edit ✅ Should work\n";
echo "   - Try to edit: /admin/posts/{$postByMod2->id}/edit ✅ Should work\n";
echo "\n";

echo "=================================\n";
echo "Test completed!\n";
echo "=================================\n";

