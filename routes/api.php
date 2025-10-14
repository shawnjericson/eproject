<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\MonumentController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\HealthController;

/*
|--------------------------------------------------------------------------
| Health Check Routes (for maintenance mode detection)
|--------------------------------------------------------------------------
*/
Route::get('/health', [HealthController::class, 'check'])->name('api.health');
Route::get('/status', [HealthController::class, 'status'])->name('api.status');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Public content routes (rate-limited)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::get('/monuments', [MonumentController::class, 'index']);
    Route::get('/monuments/{monument}', [MonumentController::class, 'show']);
    Route::get('/monuments/{monument}/feedbacks', [MonumentController::class, 'getFeedbacks']);
    Route::get('/monuments/zones', [MonumentController::class, 'zones']);
    Route::post('/monuments/{monument}/feedback', [MonumentController::class, 'submitFeedback']);
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::get('/gallery/categories', [GalleryController::class, 'categories']);
    Route::get('/feedback', [FeedbackController::class, 'index']); // Public - show all feedbacks
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show']); // Public - show single feedback
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/settings', [SiteSettingController::class, 'index']);
});

// Visitor tracking routes (stricter rate limit)
Route::middleware('throttle:20,1')->group(function () {
    Route::post('/visitor/track', [VisitorController::class, 'track']); // Track visitor by IP
    Route::get('/visitor/count', [VisitorController::class, 'count']); // Get current count
    Route::get('/visitor/stats', [VisitorController::class, 'stats']); // Get detailed stats
});

// Contact routes (rate-limited)
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/contact', [ContactController::class, 'store']); // Public - submit contact form
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Public feedback badge (no auth required)
    Route::get('/feedback-count', [NotificationController::class, 'feedbackUnreadCount']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/{id}', [NotificationController::class, 'show']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/notifications/feedback-unread-count', [NotificationController::class, 'feedbackUnreadCount']);
        Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // Posts management
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update'])->middleware('check.ownership');
    Route::patch('posts/{post}', [PostController::class, 'update'])->middleware('check.ownership');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['check.ownership', 'prevent.approved.deletion']);
    Route::post('/posts/{post}/approve', [PostController::class, 'approve'])->middleware('admin.approval');
    Route::post('/posts/{post}/reject', [PostController::class, 'reject'])->middleware('admin.approval');

    // Monuments management
    Route::post('monuments', [MonumentController::class, 'store']);
    Route::put('monuments/{monument}', [MonumentController::class, 'update'])->middleware('check.ownership');
    Route::patch('monuments/{monument}', [MonumentController::class, 'update'])->middleware('check.ownership');
    Route::delete('/monuments/{monument}', [MonumentController::class, 'destroy'])->middleware(['check.ownership', 'prevent.approved.deletion']);
    Route::post('/monuments/{monument}/approve', [MonumentController::class, 'approve'])->middleware('admin.approval');

    // Gallery management
    Route::post('gallery', [GalleryController::class, 'store']);
    Route::get('gallery/{gallery}', [GalleryController::class, 'show']);
    Route::put('gallery/{gallery}', [GalleryController::class, 'update'])->middleware('check.ownership');
    Route::patch('gallery/{gallery}', [GalleryController::class, 'update'])->middleware('check.ownership');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->middleware('check.ownership');

    // Feedback management (admin only)
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy']);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // User management
        Route::apiResource('users', UserController::class);

        // Site settings management
        Route::get('/settings/{key}', [SiteSettingController::class, 'show']);
        Route::post('/settings', [SiteSettingController::class, 'store']);
        Route::put('/settings/{key}', [SiteSettingController::class, 'update']);
        Route::delete('/settings/{key}', [SiteSettingController::class, 'destroy']);
    });
});
