<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\MonumentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\ContactController;

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

// CSRF Token Refresh Route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Authentication Routes (with locale middleware)
Route::middleware('locale')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('password.email');
    Route::get('/security-questions', [ForgotPasswordController::class, 'showSecurityQuestions'])->name('password.security-questions');
    Route::post('/security-questions', [ForgotPasswordController::class, 'verifySecurityQuestions'])->name('password.verify-security');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
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

    // Visitor Statistics (Admin Only)
    Route::middleware(['admin.only'])->group(function () {
        Route::get('visitors', [VisitorController::class, 'index'])->name('visitors.index');
        Route::get('visitors/recent', [VisitorController::class, 'recent'])->name('visitors.recent');
        Route::delete('visitors/{id}', [VisitorController::class, 'destroy'])->name('visitors.destroy');
        Route::post('visitors/clear-old', [VisitorController::class, 'clearOld'])->name('visitors.clear-old');
        Route::get('visitors/export', [VisitorController::class, 'export'])->name('visitors.export');
    });

    // Contact Messages Management
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
        Route::post('/{contact}/reply', [ContactController::class, 'reply'])->name('reply');
        Route::patch('/{contact}/status', [ContactController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
    });

    // Profile Management (Admin & Moderator)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/security-questions', [ProfileController::class, 'showSecurityQuestions'])->name('security-questions');
        Route::put('/security-questions', [ProfileController::class, 'updateSecurityQuestions'])->name('security-questions.update');
    });
});
