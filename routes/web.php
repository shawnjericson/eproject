<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\MonumentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect home to login (React frontend handles public routes)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Debug upload route
Route::get('/debug-upload', function () {
    return view('debug-upload');
})->name('debug.upload');

// Test upload route
Route::post('/test-upload', [App\Http\Controllers\TestUploadController::class, 'store'])->name('test.upload.store');

// Final test route
Route::get('/final-test', function () {
    return view('final-test');
})->name('final.test');

// Cloudinary test routes
Route::get('/cloudinary-test', function () {
    return view('cloudinary-test');
})->name('cloudinary.test');

Route::post('/cloudinary-test', [App\Http\Controllers\CloudinaryTestController::class, 'store'])->name('cloudinary.test.store');

// Test monument form
Route::get('/test-monument-form', function () {
    return view('test-monument-form');
})->name('test.monument.form');

// Check PHP config from web server
Route::get('/check-config', function () {
    return response()->json([
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'memory_limit' => ini_get('memory_limit'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'config_file' => php_ini_loaded_file(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ]);
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin', 'locale'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts Management
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('check.ownership');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware('check.ownership');
    Route::patch('posts/{post}', [PostController::class, 'update'])->middleware('check.ownership');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware(['check.ownership', 'prevent.approved.deletion']);
    Route::post('posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve')->middleware('admin.approval');
    Route::post('posts/{post}/reject', [PostController::class, 'reject'])->name('posts.reject')->middleware('admin.approval');
    Route::post('posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');

    // Monuments Management
    Route::get('monuments', [MonumentController::class, 'index'])->name('monuments.index');
    Route::get('monuments/create', [MonumentController::class, 'create'])->name('monuments.create')->middleware(\App\Http\Middleware\HandlePostTooLarge::class);
    Route::post('monuments', [MonumentController::class, 'store'])->name('monuments.store')->middleware(\App\Http\Middleware\HandlePostTooLarge::class);
    Route::get('monuments/{monument}', [MonumentController::class, 'show'])->name('monuments.show');
    Route::get('monuments/{monument}/edit', [MonumentController::class, 'edit'])->name('monuments.edit')->middleware(['check.ownership', \App\Http\Middleware\HandlePostTooLarge::class]);
    Route::put('monuments/{monument}', [MonumentController::class, 'update'])->name('monuments.update')->middleware(['check.ownership', \App\Http\Middleware\HandlePostTooLarge::class]);
    Route::patch('monuments/{monument}', [MonumentController::class, 'update'])->middleware(['check.ownership', \App\Http\Middleware\HandlePostTooLarge::class]);
    Route::delete('monuments/{monument}', [MonumentController::class, 'destroy'])->name('monuments.destroy')->middleware(['check.ownership', 'prevent.approved.deletion', \App\Http\Middleware\HandlePostTooLarge::class]);
    Route::post('monuments/{monument}/approve', [MonumentController::class, 'approve'])->name('monuments.approve')->middleware('admin.approval');

    // Gallery Management
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('gallery/{gallery}', [GalleryController::class, 'show'])->name('gallery.show');
    Route::get('gallery/{gallery}/edit', [GalleryController::class, 'edit'])->name('gallery.edit')->middleware('check.ownership');
    Route::put('gallery/{gallery}', [GalleryController::class, 'update'])->name('gallery.update')->middleware('check.ownership');
    Route::patch('gallery/{gallery}', [GalleryController::class, 'update'])->middleware('check.ownership');
    Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy')->middleware('check.ownership');

    // Feedbacks Management
    Route::resource('feedbacks', FeedbackController::class)->only(['index', 'show', 'destroy']);

    // Users Management (Admin Only)
    Route::middleware(['admin.only'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Site Settings Management (Admin Only)
    Route::middleware(['admin.only'])->group(function () {
        Route::resource('settings', SiteSettingController::class)->except(['show']);
    });

    // Profile Management (Admin & Moderator)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
});

// Test routes
Route::get('/test-simple-form', function () {
    return view('test-simple-form');
});

Route::post('/test-monument-no-csrf', function (Illuminate\Http\Request $request) {
    // Test monument creation without CSRF for debugging
    $data = [
        'title' => $request->title ?: 'Test Monument',
        'description' => $request->description ?: 'Test description',
        'location' => $request->location ?: 'Test location',
        'zone' => $request->zone ?: 'North',
        'content' => $request->content ?: '<p>Test content</p>',
        'status' => $request->status ?: 'draft',
        'created_by' => 1,
    ];

    try {
        $monument = \App\Models\Monument::create($data);
        return response()->json([
            'success' => true,
            'monument_id' => $monument->id,
            'message' => 'Monument created successfully!',
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'data' => $data
        ], 500);
    }
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/check-config', function () {
    return response()->json([
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'memory_limit' => ini_get('memory_limit'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'config_file' => php_ini_loaded_file(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ]);
});
