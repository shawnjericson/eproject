# 📝 Posts Management System - Hướng dẫn chi tiết

## 🏗️ Tổng quan kiến trúc

Hệ thống Posts Management được thiết kế để quản lý nội dung đa ngôn ngữ với workflow approval và phân quyền chi tiết:

- **Model**: `Post.php`, `PostTranslation.php` - Quản lý bài viết và bản dịch
- **Controller**: `PostController.php` (Admin), `PostController.php` (API) - Xử lý CRUD
- **Views**: Blade templates với TinyMCE editor
- **Features**: Multilingual, Image upload, Status workflow, Permission-based access

---

## 🏗️ 1. MODEL - Post.php

### 📊 Database Structure
```sql
-- Bảng posts (Main content table)
CREATE TABLE posts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,           -- Fallback title
    content LONGTEXT NOT NULL,             -- Fallback content  
    image VARCHAR(255) NULL,               -- Featured image
    created_by BIGINT NOT NULL,            -- Foreign key to users
    status ENUM('draft', 'pending', 'approved') DEFAULT 'draft',
    published_at TIMESTAMP NULL,           -- Auto-set when approved
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_created_by (created_by)
);

-- Bảng post_translations (Multilingual content)
CREATE TABLE post_translations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    post_id BIGINT NOT NULL,
    language ENUM('vi', 'en') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,                 -- SEO description
    content LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_post_language (post_id, language),
    INDEX idx_language (language)
);
```

### 🔗 Relationships
```php
// Post belongs to User (creator)
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

// Post has many PostTranslations (One-to-Many)
public function translations()
{
    return $this->hasMany(PostTranslation::class);
}

// Get specific language translation
public function translation($language = 'en')
{
    return $this->translations()->where('language', $language)->first();
}
```

### ⚙️ Query Scopes
```php
// Scope for published posts (public access)
public function scopePublished($query)
{
    return $query->where('status', 'approved')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
}

// Scope for pending posts (moderator review)
public function scopePending($query)
{
    return $query->where('status', 'pending');
}

// Scope for approved posts
public function scopeApproved($query)
{
    return $query->where('status', 'approved');
}
```

### 🌐 Multilingual Methods
```php
// Get title in specific language with fallback
public function getTitle($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->title : $this->title;
}

// Get description in specific language
public function getDescription($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->description : null;
}

// Get content in specific language with fallback
public function getContent($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->content : $this->content;
}
```

### 🖼️ Image Handling
```php
// Get image URL (supports both Cloudinary and local storage)
public function getImageUrlAttribute()
{
    if (!$this->image) {
        return null;
    }

    // If it's already a full URL (Cloudinary), return as is
    if (filter_var($this->image, FILTER_VALIDATE_URL)) {
        return $this->image;
    }

    // If it's a local path, add storage prefix
    return asset('storage/' . $this->image);
}
```

### 🔒 Security & Casting
```php
protected $fillable = [
    'title',
    'content', 
    'image',
    'created_by',
    'status',
    'published_at',
];

protected $casts = [
    'published_at' => 'datetime',
];
```

---

## 🏗️ 2. MODEL - PostTranslation.php

### 📊 Translation Structure
```php
class PostTranslation extends Model
{
    protected $fillable = [
        'post_id',
        'language',
        'title',
        'description',
        'content',
    ];

    // Translation belongs to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
```

---

## 🎮 3. CONTROLLER - Admin/PostController.php

### 📋 Index Method - Danh sách Posts
```php
public function index(Request $request)
{
    $query = Post::with(['creator', 'translations']);

    // 🔍 Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 Search by title/content
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('content', 'like', '%' . $request->search . '%');
        });
    }

    // 🔍 Filter by creator (for moderators to see only their posts)
    if (auth()->user()->role === 'moderator') {
        $query->where('created_by', auth()->id());
    }

    $posts = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.posts.index', compact('posts'));
}
```

### ➕ Store Method - Tạo Post mới
```php
public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'language' => 'required|in:en,vi',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'status' => 'required|in:draft,pending,approved',
    ]);

    // 📝 Create main post
    $postData = [
        'title' => $request->title,     // Fallback title
        'content' => $request->content, // Fallback content
        'status' => $request->status,
        'created_by' => auth()->id(),
    ];

    // 🖼️ Handle image upload (Cloudinary)
    if ($request->hasFile('image')) {
        try {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'), 
                'posts'
            );

            if ($uploadResult['success']) {
                $postData['image'] = $uploadResult['url'];
            } else {
                return redirect()->back()
                    ->with('error', 'Image upload failed: ' . $uploadResult['error']);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Image upload failed.');
        }
    }

    // 📅 Set published_at if approved
    if ($postData['status'] === 'approved') {
        $postData['published_at'] = now();
    }

    // 💾 Create post
    $post = Post::create($postData);

    // 🌐 Create translation
    PostTranslation::create([
        'post_id' => $post->id,
        'language' => $request->language,
        'title' => $request->title,
        'description' => $request->description,
        'content' => $request->content,
    ]);

    return redirect()->route('admin.posts.index')
                   ->with('success', 'Post created successfully!');
}
```

### ✏️ Update Method - Cập nhật Post
```php
public function update(Request $request, Post $post)
{
    // ✅ Validation
    $request->validate([
        'status' => 'required|in:draft,pending,approved',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'translations' => 'required|array',
        'translations.*.language' => 'required|in:vi,en',
        'translations.*.title' => 'required|string|max:255',
        'translations.*.content' => 'required|string',
    ]);

    // 📝 Update main post
    $postData = ['status' => $request->status];

    // 🖼️ Handle image upload
    if ($request->hasFile('image')) {
        try {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'), 
                'posts'
            );

            if ($uploadResult['success']) {
                $postData['image'] = $uploadResult['url'];
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Image upload failed.');
        }
    }

    // 📅 Set published_at when first approved
    if ($postData['status'] === 'approved' && $post->status !== 'approved') {
        $postData['published_at'] = now();
    }

    $post->update($postData);

    // 🌐 Update translations
    foreach ($request->translations as $translationData) {
        if (!empty($translationData['title']) && !empty($translationData['content'])) {
            PostTranslation::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'language' => $translationData['language']
                ],
                [
                    'title' => $translationData['title'],
                    'description' => $translationData['description'],
                    'content' => $translationData['content'],
                ]
            );
        }
    }

    // 🔄 Update fallback title and content from first translation
    $firstTranslation = $request->translations[0];
    $post->update([
        'title' => $firstTranslation['title'],
        'content' => $firstTranslation['content'],
    ]);

    return redirect()->route('admin.posts.index')
                   ->with('success', 'Post updated successfully!');
}
```

### 🗑️ Destroy Method - Xóa Post
```php
public function destroy(Post $post)
{
    $user = auth()->user();

    // 🔒 Permission check: Moderator can only delete non-approved content
    if ($user->role === 'moderator' && $post->status === 'approved') {
        abort(403, 'Cannot delete approved content. Only administrators can delete approved content.');
    }

    // 🖼️ Delete image (only local images, not Cloudinary URLs)
    if ($post->image && !filter_var($post->image, FILTER_VALIDATE_URL)) {
        Storage::disk('public')->delete($post->image);
    }

    // 💾 Delete post (translations will be deleted via CASCADE)
    $post->delete();
    
    return redirect()->route('admin.posts.index')
                   ->with('success', 'Post deleted successfully!');
}
```

---

## 🌐 4. API CONTROLLER - Api/PostController.php

### 📋 API Index - Public Posts
```php
public function index(Request $request)
{
    $query = Post::with(['creator', 'translations']);

    // 🔒 For public API, only show published posts
    if (!$request->user() || !in_array($request->user()->role, ['admin', 'moderator'])) {
        $query->published();
    } else {
        // For admin/moderator, allow filtering by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    }

    // 🔍 Search functionality
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('content', 'like', '%' . $request->search . '%');
        });
    }

    // 📄 Pagination
    $posts = $query->orderBy('published_at', 'desc')
                  ->paginate($request->get('per_page', 10));

    // 🌐 Transform data for API response
    $posts->getCollection()->transform(function ($post) use ($request) {
        $language = $request->get('language', 'vi');
        
        return [
            'id' => $post->id,
            'title' => $post->getTitle($language),
            'description' => $post->getDescription($language),
            'content' => $post->getContent($language),
            'image_url' => $post->image_url,
            'status' => $post->status,
            'published_at' => $post->published_at,
            'creator' => [
                'id' => $post->creator->id,
                'name' => $post->creator->name,
                'avatar_url' => $post->creator->avatar_url,
            ],
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];
    });

    return response()->json($posts);
}
```

### 📱 API Show - Single Post
```php
public function show(Request $request, Post $post)
{
    // 🔒 Check if post is published for public access
    if (!$request->user() && $post->status !== 'approved') {
        return response()->json(['error' => 'Post not found'], 404);
    }

    $post->load(['creator', 'translations']);
    $language = $request->get('language', 'vi');

    return response()->json([
        'id' => $post->id,
        'title' => $post->getTitle($language),
        'description' => $post->getDescription($language),
        'content' => $post->getContent($language),
        'image_url' => $post->image_url,
        'status' => $post->status,
        'published_at' => $post->published_at,
        'creator' => [
            'id' => $post->creator->id,
            'name' => $post->creator->name,
            'avatar_url' => $post->creator->avatar_url,
        ],
        'translations' => $post->translations,
        'created_at' => $post->created_at,
        'updated_at' => $post->updated_at,
    ]);
}
```

---

## 🎨 5. VIEWS - User Interface

### 📋 Posts Index View Structure
```blade
@extends('layouts.admin')

@section('title', 'Posts Management')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Posts Management</h1>
        <p class="text-muted mb-0">Manage blog posts and articles</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Create New Post
    </a>
</div>

{{-- Filter Form --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3">
            {{-- Search --}}
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search posts..." 
                       value="{{ request('search') }}">
            </div>
            
            {{-- Status Filter --}}
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Posts Table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Status</th>
                        <th>Author</th>
                        <th>Languages</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($post->image_url)
                                <img src="{{ $post->image_url }}" 
                                     alt="{{ $post->title }}" 
                                     class="rounded me-3" 
                                     width="60" height="40" 
                                     style="object-fit: cover;">
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ Str::limit($post->title, 50) }}</div>
                                    <div class="text-muted small">{{ Str::limit(strip_tags($post->content), 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ 
                                $post->status == 'approved' ? 'success' : 
                                ($post->status == 'pending' ? 'warning' : 'secondary') 
                            }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $post->creator->avatar_url }}" 
                                     alt="{{ $post->creator->name }}" 
                                     class="rounded-circle me-2" 
                                     width="30" height="30">
                                <span>{{ $post->creator->name }}</span>
                            </div>
                        </td>
                        <td>
                            @foreach($post->translations as $translation)
                                <span class="badge bg-info me-1">
                                    {{ strtoupper($translation->language) }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            @if($post->published_at)
                                {{ $post->published_at->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not published</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.posts.show', $post) }}" 
                                   class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                   class="btn btn-sm btn-outline-secondary">Edit</a>
                                @if(auth()->user()->role === 'admin' || $post->status !== 'approved')
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-file-text fs-1 d-block mb-2"></i>
                                No posts found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
```

---

## 🛣️ 6. ROUTES - URL Mapping

### 🔐 Admin Routes
```php
// Admin Posts Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'locale'])->group(function () {
    Route::resource('posts', PostController::class);
    // Creates routes:
    // GET    /admin/posts          -> index
    // GET    /admin/posts/create   -> create
    // POST   /admin/posts          -> store
    // GET    /admin/posts/{post}   -> show
    // GET    /admin/posts/{post}/edit -> edit
    // PUT    /admin/posts/{post}   -> update
    // DELETE /admin/posts/{post}   -> destroy
});
```

### 🌐 API Routes
```php
// Public API Routes
Route::prefix('api')->group(function () {
    // Public posts (published only)
    Route::get('/posts', [Api\PostController::class, 'index']);
    Route::get('/posts/{post}', [Api\PostController::class, 'show']);
    
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/posts', [Api\PostController::class, 'store']);
        Route::put('/posts/{post}', [Api\PostController::class, 'update']);
        Route::delete('/posts/{post}', [Api\PostController::class, 'destroy']);
    });
});
```

---

## 🔒 7. MIDDLEWARE & PERMISSIONS

### 🛡️ Permission Logic
```php
// In PostController methods:

// Create: Any authenticated user can create posts
// Status depends on user role:
// - Admin: Can create with any status (draft, pending, approved)
// - Moderator: Can create draft/pending, needs approval for published

// Read: 
// - Public: Only approved posts
// - Admin/Moderator: All posts (with filtering)

// Update:
// - Admin: Can update any post
// - Moderator: Can only update their own posts

// Delete:
// - Admin: Can delete any post
// - Moderator: Can only delete non-approved posts (their own)
```

### 🔄 Status Workflow
```
Draft → Pending → Approved
  ↑        ↑         ↑
  └────────┴─────────┘
  (Admin can change to any status)
  (Moderator can only draft/pending)
```

---

## 📊 8. TỔNG KẾT POSTS MANAGEMENT

### 🎯 Key Features

#### ✅ **Multilingual Support**
- Vietnamese and English translations
- Fallback content system
- Language-specific API responses
- Translation management interface

#### ✅ **Content Management**
- Rich text editor (TinyMCE)
- Image upload with Cloudinary integration
- SEO-friendly descriptions
- Draft/Pending/Approved workflow

#### ✅ **Permission System**
- Role-based access control
- Status-based deletion restrictions
- Creator ownership validation
- Public/Private content separation

#### ✅ **Advanced Features**
- Search and filtering
- Pagination
- Image optimization
- API endpoints for frontend
- Responsive admin interface

### 🔄 **Complete Workflow**

```
1. 👤 User creates post → Status: Draft
2. 📝 User submits for review → Status: Pending  
3. 👨‍💼 Admin reviews → Status: Approved + published_at set
4. 🌐 Post appears on public API/frontend
5. 📱 Frontend fetches via API with language parameter
6. 🎨 Content displayed with proper translations
```

### 🚀 **Technical Highlights**

- **Database Design**: Separate tables for posts and translations
- **Image Handling**: Cloudinary integration with local fallback
- **API Design**: RESTful endpoints with language support
- **Security**: CSRF protection, input validation, permission checks
- **Performance**: Eager loading, pagination, efficient queries
- **UX**: Rich editor, drag-drop upload, real-time preview

---

## 🎯 Next: Monuments Management

Phần tiếp theo sẽ cover **Monuments Management** với:
- Geographic data (coordinates, zones)
- Gallery system integration
- Feedback/Rating system
- World wonders classification
- Advanced search by location
