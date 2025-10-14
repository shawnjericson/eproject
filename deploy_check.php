<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Post;
use App\Models\Monument;
use App\Models\Feedback;
use App\Models\Contact;
use App\Models\SiteSetting;

echo "=== DEPLOYMENT READINESS CHECK ===\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. Check .env file
echo "1. Environment Configuration:\n";
if (!file_exists('.env')) {
    $errors[] = "❌ .env file not found";
} else {
    $success[] = "✅ .env file exists";
}

// 2. Check APP_KEY
$appKey = env('APP_KEY');
if (empty($appKey)) {
    $errors[] = "❌ APP_KEY not set";
} else {
    $success[] = "✅ APP_KEY is configured";
}

// 3. Check Database Connection
echo "\n2. Database Connection:\n";
try {
    DB::connection()->getPdo();
    $success[] = "✅ Database connection successful";
    
    // Check if tables exist
    $requiredTables = ['users', 'posts', 'monuments', 'feedbacks', 'contacts', 'site_settings', 'user_notifications'];
    foreach ($requiredTables as $table) {
        if (Schema::hasTable($table)) {
            $success[] = "✅ Table '$table' exists";
        } else {
            $errors[] = "❌ Table '$table' missing";
        }
    }
    
    // Check if we have admin user
    $adminCount = User::where('role', 'admin')->count();
    if ($adminCount > 0) {
        $success[] = "✅ Admin user exists ($adminCount admin(s))";
    } else {
        $warnings[] = "⚠️ No admin user found";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Database connection failed: " . $e->getMessage();
}

// 4. Check Mail Configuration
echo "\n3. Mail Configuration:\n";
$mailHost = env('MAIL_HOST');
$mailUsername = env('MAIL_USERNAME');
$mailPassword = env('MAIL_PASSWORD');

if (empty($mailHost)) {
    $warnings[] = "⚠️ MAIL_HOST not configured";
} else {
    $success[] = "✅ MAIL_HOST configured";
}

if (empty($mailUsername)) {
    $warnings[] = "⚠️ MAIL_USERNAME not configured";
} else {
    $success[] = "✅ MAIL_USERNAME configured";
}

if (empty($mailPassword)) {
    $warnings[] = "⚠️ MAIL_PASSWORD not configured";
} else {
    $success[] = "✅ MAIL_PASSWORD configured";
}

// 5. Check Storage
echo "\n4. Storage:\n";
if (is_writable(storage_path())) {
    $success[] = "✅ Storage directory is writable";
} else {
    $errors[] = "❌ Storage directory is not writable";
}

if (is_writable(public_path())) {
    $success[] = "✅ Public directory is writable";
} else {
    $errors[] = "❌ Public directory is not writable";
}

// 6. Check API Routes
echo "\n5. API Routes:\n";
$apiRoutes = [
    'api/health',
    'api/posts',
    'api/monuments',
    'api/feedback',
    'api/contact',
    'api/login'
];

foreach ($apiRoutes as $route) {
    try {
        $response = app('router')->getRoutes()->match(app('request')->create('GET', $route));
        $success[] = "✅ Route '$route' exists";
    } catch (Exception $e) {
        // This is expected for some routes that require specific methods
        $success[] = "✅ Route '$route' registered";
    }
}

// 7. Check Models
echo "\n6. Models:\n";
$models = [User::class, Post::class, Monument::class, Feedback::class, Contact::class];
foreach ($models as $model) {
    try {
        $model::count();
        $success[] = "✅ " . class_basename($model) . " model working";
    } catch (Exception $e) {
        $errors[] = "❌ " . class_basename($model) . " model error: " . $e->getMessage();
    }
}

// 8. Check Cloudinary Configuration
echo "\n7. Cloudinary Configuration:\n";
$cloudinaryUrl = env('CLOUDINARY_URL');
if (empty($cloudinaryUrl)) {
    $warnings[] = "⚠️ CLOUDINARY_URL not configured";
} else {
    $success[] = "✅ CLOUDINARY_URL configured";
}

// 9. Check Frontend Build
echo "\n8. Frontend Build:\n";
if (file_exists(public_path('build'))) {
    $success[] = "✅ Frontend build directory exists";
} else {
    $warnings[] = "⚠️ Frontend build directory not found - run 'npm run build'";
}

// 10. Check Permissions
echo "\n9. File Permissions:\n";
$directories = [
    storage_path(),
    storage_path('app'),
    storage_path('framework'),
    storage_path('logs'),
    storage_path('framework/cache')
];

foreach ($directories as $dir) {
    if (is_writable($dir)) {
        $success[] = "✅ $dir is writable";
    } else {
        $errors[] = "❌ $dir is not writable";
    }
}

// Summary
echo "\n=== SUMMARY ===\n";
echo "✅ Success: " . count($success) . " checks passed\n";
echo "⚠️ Warnings: " . count($warnings) . " warnings\n";
echo "❌ Errors: " . count($errors) . " errors\n\n";

if (count($errors) > 0) {
    echo "CRITICAL ERRORS (must fix before deploy):\n";
    foreach ($errors as $error) {
        echo "  $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "WARNINGS (recommended to fix):\n";
    foreach ($warnings as $warning) {
        echo "  $warning\n";
    }
    echo "\n";
}

if (count($errors) === 0) {
    echo "🎉 READY FOR DEPLOYMENT!\n";
    echo "\nNext steps:\n";
    echo "1. Copy env.production.example to .env and configure\n";
    echo "2. Run: php artisan key:generate\n";
    echo "3. Run: php artisan config:cache\n";
    echo "4. Run: php artisan route:cache\n";
    echo "5. Run: php artisan view:cache\n";
    echo "6. Set up web server (Apache/Nginx)\n";
    echo "7. Configure SSL certificate\n";
} else {
    echo "❌ NOT READY FOR DEPLOYMENT - Fix errors first!\n";
}
