# 📚 User Management & Authentication - Hướng dẫn chi tiết

## 🏗️ Tổng quan kiến trúc MVC

Hệ thống User Management được xây dựng theo mô hình MVC (Model-View-Controller) của Laravel với các thành phần chính:

- **Model**: `User.php` - Quản lý dữ liệu và business logic
- **Controller**: `UserController.php`, `LoginController.php` - Xử lý request/response
- **View**: Blade templates - Giao diện người dùng
- **Middleware**: Bảo mật và phân quyền
- **Routes**: Định tuyến URL

---

## 🏗️ 1. MODEL - User.php

### 📊 Database Structure
```sql
-- Bảng users
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderator') DEFAULT 'moderator',
    status ENUM('active', 'inactive') DEFAULT 'active',
    avatar VARCHAR(255) NULL,
    phone VARCHAR(255) NULL,
    bio TEXT NULL,
    address TEXT NULL,
    date_of_birth DATE NULL,
    security_question_1 VARCHAR(255) NULL,
    security_answer_1 VARCHAR(255) NULL,
    security_question_2 VARCHAR(255) NULL,
    security_answer_2 VARCHAR(255) NULL,
    security_question_3 VARCHAR(255) NULL,
    security_answer_3 VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 🔗 Relationships (Quan hệ)
```php
// User có nhiều Posts (One-to-Many)
public function posts()
{
    return $this->hasMany(Post::class, 'created_by');
}

// User có nhiều Monuments (One-to-Many)
public function monuments()
{
    return $this->hasMany(Monument::class, 'created_by');
}
```

### ⚙️ Methods & Attributes
```php
// Kiểm tra quyền admin
public function isAdmin()
{
    return $this->role === 'admin';
}

// Kiểm tra quyền moderator
public function isModerator()
{
    return $this->role === 'moderator';
}

// Lấy URL avatar (hỗ trợ Cloudinary + local storage)
public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        // Nếu là URL đầy đủ (Cloudinary)
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        // Nếu là đường dẫn local
        return asset('storage/' . $this->avatar);
    }

    // Avatar mặc định theo role
    return $this->role === 'admin'
        ? asset('images/default-admin-avatar.png')
        : asset('images/default-moderator-avatar.png');
}

// Tính tuổi từ ngày sinh
public function getAgeAttribute()
{
    if (!$this->date_of_birth) {
        return null;
    }
    return $this->date_of_birth->age;
}

// Tính phần trăm hoàn thành profile
public function getProfileCompletionAttribute()
{
    $fields = ['name', 'email', 'avatar', 'phone', 'bio', 'address', 'date_of_birth'];
    $completed = 0;

    foreach ($fields as $field) {
        if (!empty($this->$field)) {
            $completed++;
        }
    }

    return round(($completed / count($fields)) * 100);
}
```

### 🔒 Security Features
```php
// Mass assignment protection
protected $fillable = [
    'name', 'email', 'password', 'role', 'status',
    'avatar', 'phone', 'bio', 'address', 'date_of_birth',
    'security_question_1', 'security_answer_1',
    'security_question_2', 'security_answer_2',
    'security_question_3', 'security_answer_3',
];

// Ẩn sensitive data khi serialize
protected $hidden = [
    'password',
    'remember_token',
];

// Auto casting
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed', // Tự động hash password
    'date_of_birth' => 'date',
];
```

---

## 🎮 2. CONTROLLER - UserController.php

### 📋 Index Method - Danh sách User
```php
public function index(Request $request)
{
    // Khởi tạo query với eager loading
    $query = User::with(['posts', 'monuments']);

    // 🔍 Filter by role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // 🔍 Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 Search by name/email
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    // Phân trang và sắp xếp
    $users = $query->orderBy('created_at', 'desc')->paginate(10);

    // 📊 Tính toán thống kê
    $stats = [
        'total' => User::count(),
        'admins' => User::where('role', 'admin')->count(),
        'moderators' => User::where('role', 'moderator')->count(),
        'active' => User::where('status', 'active')->count(),
    ];

    return view('admin.users.index', compact('users', 'stats'));
}
```

### ➕ Store Method - Tạo User mới
```php
public function store(Request $request)
{
    // ✅ Validation rules
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:admin,moderator',
        'status' => 'required|in:active,inactive',
    ]);

    // 💾 Tạo user mới
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Auto hash
        'role' => $request->role,
        'status' => $request->status,
    ]);

    // 🔄 Redirect với success message
    return redirect()->route('admin.users.index')
                   ->with('success', 'User created successfully!');
}
```

### ✏️ Update Method - Cập nhật User
```php
public function update(Request $request, User $user)
{
    // ✅ Validation với ignore current user cho email unique
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'string', 'email', 'max:255', 
                   Rule::unique('users')->ignore($user->id)],
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,moderator',
        'status' => 'required|in:active,inactive',
    ]);

    // Chuẩn bị data
    $data = $request->only(['name', 'email', 'role', 'status']);

    // Chỉ update password nếu có nhập
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 💾 Update user
    $user->update($data);

    return redirect()->route('admin.users.index')
                   ->with('success', 'User updated successfully!');
}
```

---

## 🔐 3. AUTHENTICATION CONTROLLERS

### 🚪 LoginController
```php
public function login(Request $request)
{
    try {
        // ✅ Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // 🔐 Thử đăng nhập
        if (Auth::attempt($credentials, $remember)) {
            // Bảo mật: regenerate session ID
            $request->session()->regenerate();

            // ✅ Kiểm tra user có active không
            if (Auth::user()->status !== 'active') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated.'],
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        // ❌ Đăng nhập thất bại
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);

    } catch (\Illuminate\Session\TokenMismatchException $e) {
        // 🔒 CSRF token mismatch
        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['email' => 'Session expired. Please try again.']);
    } catch (\Exception $e) {
        // 🚨 General error handling
        Log::error('Login error: ' . $e->getMessage());
        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['email' => 'An error occurred. Please try again.']);
    }
}
```

### 📝 RegisterController
```php
protected function create(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => 'moderator', // Default role cho user mới
        'status' => 'active',   // Auto-activate
    ]);
}
```

---

## 🎨 4. VIEWS - User Interface

### 📋 User Index View Structure
```blade
@extends('layouts.admin')

@section('title', __('admin.users_management'))

@section('content')
{{-- Header với title và nút Add New --}}
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.users_management') }}</h1>
        <p class="text-muted mb-0">Manage system users and permissions</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-minimal btn-primary">
        <i class="bi bi-plus"></i> {{ __('admin.add_new') }} User
    </a>
</div>

{{-- Stats Cards hiển thị thống kê --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card-minimal text-center">
            <div class="card-body">
                <h5 class="card-title">{{ $stats['total'] ?? 0 }}</h5>
                <p class="card-text text-muted">Total Users</p>
            </div>
        </div>
    </div>
    {{-- Tương tự cho admins, moderators, active users --}}
</div>

{{-- Filter Form --}}
<div class="card-minimal mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            {{-- Search Input --}}
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name or email..." 
                       value="{{ request('search') }}">
            </div>
            
            {{-- Role Filter --}}
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                </select>
            </div>
            
            {{-- Status Filter --}}
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card-minimal">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Posts</th>
                        <th>Monuments</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle me-3" 
                                     width="40" height="40">
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->posts_count ?? 0 }}</td>
                        <td>{{ $user->monuments_count ?? 0 }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No users found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
```

---

## 🛣️ 5. ROUTES - URL Mapping

### 🔐 Authentication Routes
```php
// Authentication Routes (public, với locale middleware)
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
```

### 👥 Admin Routes
```php
// Admin Routes (protected với auth middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'locale'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users Management (chỉ Admin mới được truy cập)
    Route::middleware(['admin.only'])->group(function () {
        Route::resource('users', UserController::class);
        // Tạo ra các routes:
        // GET    /admin/users          -> index    (danh sách users)
        // GET    /admin/users/create   -> create   (form tạo user)
        // POST   /admin/users          -> store    (lưu user mới)
        // GET    /admin/users/{user}   -> show     (xem chi tiết user)
        // GET    /admin/users/{user}/edit -> edit  (form sửa user)
        // PUT    /admin/users/{user}   -> update   (cập nhật user)
        // DELETE /admin/users/{user}   -> destroy  (xóa user)
    });
});
```

---

## 🔒 6. MIDDLEWARE - Bảo mật

### 🛡️ AdminOnlyMiddleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Kiểm tra user đã đăng nhập và có role admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can access this resource.');
        }

        return $next($request);
    }
}
```

### 🌐 SetLocale Middleware
```php
public function handle(Request $request, Closure $next)
{
    // Lấy ngôn ngữ từ query parameter hoặc session
    $locale = $request->get('lang', session('locale', 'vi'));
    
    // Validate locale
    if (!in_array($locale, ['vi', 'en'])) {
        $locale = 'vi';
    }
    
    // Set locale cho app và session
    App::setLocale($locale);
    session(['locale' => $locale]);
    
    return $next($request);
}
```

---

## 📊 7. TỔNG KẾT MÔ HÌNH MVC

### 🔄 Flow hoạt động hoàn chỉnh

```
1. 🌐 User truy cập URL: /admin/users
   ↓
2. 🛣️ Route: web.php định tuyến đến UserController@index
   ↓
3. 🔒 Middleware: 
   - auth: Kiểm tra đăng nhập
   - admin.only: Kiểm tra quyền admin
   - locale: Set ngôn ngữ
   ↓
4. 🎮 Controller: UserController@index
   - Nhận Request
   - Xử lý filters, search, pagination
   - Query database thông qua Model
   - Chuẩn bị data cho View
   ↓
5. 🏗️ Model: User.php
   - Relationships với Posts, Monuments
   - Business logic methods
   - Attributes & Accessors
   ↓
6. 🎨 View: admin.users.index.blade.php
   - Nhận data từ Controller
   - Render HTML với Blade syntax
   - Hiển thị cho user
   ↓
7. 📱 Response: HTML được trả về browser
```

### 🎯 Key Features

#### ✅ **Authentication & Authorization**
- Login/Logout với session management
- Role-based access control (Admin/Moderator)
- Status management (Active/Inactive)
- Forgot password với security questions
- CSRF protection
- Session regeneration for security

#### ✅ **User Management**
- CRUD operations (Create, Read, Update, Delete)
- Advanced filtering (role, status, search)
- Pagination với Laravel's built-in paginator
- Profile management với avatar upload
- Statistics dashboard

#### ✅ **Security Features**
- Password hashing với bcrypt
- Mass assignment protection
- Input validation
- XSS protection với Blade escaping
- SQL injection protection với Eloquent ORM
- Middleware-based access control

#### ✅ **User Experience**
- Responsive design với Bootstrap
- Real-time search và filtering
- Success/Error messages
- Avatar display với fallback
- Profile completion percentage
- Multilingual support (Vietnamese/English)

### 🔧 **Technical Highlights**

#### **Database Design**
- Proper indexing trên email (unique)
- Enum fields cho role và status
- Nullable fields cho optional data
- Timestamps cho audit trail

#### **Code Organization**
- Separation of concerns (MVC pattern)
- Reusable components
- Consistent naming conventions
- Proper error handling
- Clean and readable code

#### **Performance Optimization**
- Eager loading để tránh N+1 queries
- Pagination để handle large datasets
- Efficient database queries
- Caching strategies (có thể implement)

---

## 🚀 Next Steps

Đây là phần đầu tiên về **User Management & Authentication**. Các phần tiếp theo sẽ bao gồm:

1. **Posts Management** - Hệ thống đăng bài
2. **Monuments Management** - Quản lý di tích
3. **Gallery System** - Thư viện ảnh
4. **Feedback System** - Hệ thống đánh giá
5. **API Documentation** - REST API endpoints
6. **Frontend Integration** - React frontend

Bạn muốn tôi tiếp tục với phần nào tiếp theo? 🤔
