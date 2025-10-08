# 🏛️ Monuments Management System - Hướng dẫn chi tiết

## 🏗️ Tổng quan kiến trúc

Hệ thống Monuments Management quản lý di tích lịch sử với đầy đủ thông tin địa lý, thư viện ảnh, và hệ thống đánh giá:

- **Models**: `Monument.php`, `MonumentTranslation.php`, `Gallery.php`, `Feedback.php`
- **Features**: Multilingual content, Geographic data, Image galleries, Rating system
- **Integration**: Google Maps, Cloudinary, World wonders classification

---

## 🏗️ 1. MODEL - Monument.php

### 📊 Database Structure
```sql
-- Bảng monuments (Main monuments table)
CREATE TABLE monuments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,              -- Fallback title
    description TEXT NULL,                    -- Fallback description
    history LONGTEXT NULL,                    -- Historical information
    content LONGTEXT NULL,                    -- Detailed content
    location VARCHAR(255) NULL,               -- Address/Location
    map_embed TEXT NULL,                      -- Google Maps embed code
    zone ENUM('North', 'South', 'Central', 'East', 'West') NULL,
    latitude DECIMAL(10, 8) NULL,             -- GPS coordinates
    longitude DECIMAL(11, 8) NULL,            -- GPS coordinates
    is_world_wonder BOOLEAN DEFAULT FALSE,    -- UNESCO/World wonder status
    image VARCHAR(255) NULL,                  -- Featured image
    created_by BIGINT NOT NULL,               -- Foreign key to users
    status ENUM('draft', 'pending', 'approved') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_zone (zone),
    INDEX idx_world_wonder (is_world_wonder),
    INDEX idx_coordinates (latitude, longitude),
    INDEX idx_created_by (created_by)
);

-- Bảng monument_translations (Multilingual content)
CREATE TABLE monument_translations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    monument_id BIGINT NOT NULL,
    language ENUM('vi', 'en') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    history LONGTEXT NULL,
    content LONGTEXT NULL,
    location VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (monument_id) REFERENCES monuments(id) ON DELETE CASCADE,
    UNIQUE KEY unique_monument_language (monument_id, language),
    INDEX idx_language (language)
);

-- Bảng gallery (Monument images)
CREATE TABLE gallery (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    monument_id BIGINT NOT NULL,
    title VARCHAR(255) NULL,
    image_path VARCHAR(255) NOT NULL,
    description TEXT NULL,
    category VARCHAR(100) NULL,               -- Image category/type
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (monument_id) REFERENCES monuments(id) ON DELETE CASCADE,
    INDEX idx_monument_id (monument_id),
    INDEX idx_category (category)
);

-- Bảng feedbacks (Reviews and ratings)
CREATE TABLE feedbacks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    monument_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,         -- 1-5 stars
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (monument_id) REFERENCES monuments(id) ON DELETE CASCADE,
    INDEX idx_monument_id (monument_id),
    INDEX idx_status (status),
    INDEX idx_rating (rating),
    CHECK (rating >= 1 AND rating <= 5)
);
```

### 🔗 Relationships
```php
// Monument belongs to User (creator)
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

// Monument has many Gallery images (One-to-Many)
public function gallery()
{
    return $this->hasMany(Gallery::class);
}

// Monument has many Feedbacks (One-to-Many)
public function feedbacks()
{
    return $this->hasMany(Feedback::class);
}

// Monument has many MonumentTranslations (One-to-Many)
public function translations()
{
    return $this->hasMany(MonumentTranslation::class);
}

// Get specific language translation
public function translation($language = 'en')
{
    return $this->translations()->where('language', $language)->first();
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
    return $translation ? $translation->description : $this->description;
}

// Get history in specific language
public function getHistory($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->history : $this->history;
}

// Get content in specific language with fallback
public function getContent($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->content : $this->content;
}

// Get location in specific language
public function getLocation($language = 'en')
{
    $translation = $this->translation($language);
    return $translation ? $translation->location : $this->location;
}
```

### 🔍 Query Scopes
```php
// Scope for approved monuments (public access)
public function scopeApproved($query)
{
    return $query->where('status', 'approved');
}

// Scope for monuments by geographic zone
public function scopeByZone($query, $zone)
{
    return $query->where('zone', $zone);
}

// Scope for world wonders only
public function scopeWorldWonders($query)
{
    return $query->where('is_world_wonder', true);
}
```

### 🖼️ Image Handling
```php
// Get featured image URL (supports Cloudinary and local storage)
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

### 🔒 Security & Fillable
```php
protected $fillable = [
    'title',
    'description',
    'history',
    'content',
    'location',
    'map_embed',
    'zone',
    'latitude',
    'longitude',
    'is_world_wonder',
    'image',
    'created_by',
    'status',
];
```

---

## 🏗️ 2. MODEL - MonumentTranslation.php

### 📊 Translation Structure
```php
class MonumentTranslation extends Model
{
    protected $fillable = [
        'monument_id',
        'language',
        'title',
        'description',
        'history',
        'content',
        'location',
    ];

    // Translation belongs to Monument
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
```

---

## 🏗️ 3. MODEL - Gallery.php

### 📊 Gallery Structure
```php
class Gallery extends Model
{
    protected $table = 'gallery';
    
    protected $fillable = [
        'monument_id',
        'title',
        'image_path',
        'description',
        'category',
    ];

    // Auto-append computed attributes
    protected $appends = ['image_url', 'thumbnail_url', 'blur_hash'];

    // Gallery belongs to Monument
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
```

### 🖼️ Advanced Image Processing
```php
// Get full-size image URL
public function getImageUrlAttribute()
{
    if (!$this->image_path) {
        return null;
    }

    // Cloudinary URL - return as is
    if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
        return $this->image_path;
    }

    // Local storage - add prefix
    return asset('storage/' . $this->image_path);
}

// Get optimized thumbnail (400x300) for gallery grid
public function getThumbnailUrlAttribute()
{
    if (!$this->image_path) {
        return null;
    }

    // Cloudinary - add transformation parameters
    if (filter_var($this->image_path, FILTER_VALIDATE_URL) && 
        str_contains($this->image_path, 'cloudinary')) {
        return str_replace(
            '/upload/', 
            '/upload/w_400,h_300,c_fill,q_auto,f_auto/', 
            $this->image_path
        );
    }

    // Local storage fallback
    return $this->image_url;
}

// Get tiny blur placeholder (20x15) for progressive loading
public function getBlurHashAttribute()
{
    if (!$this->image_path) {
        return null;
    }

    // Cloudinary - create blur placeholder
    if (filter_var($this->image_path, FILTER_VALIDATE_URL) && 
        str_contains($this->image_path, 'cloudinary')) {
        return str_replace(
            '/upload/', 
            '/upload/w_20,h_15,c_fill,q_auto,f_auto,e_blur:1000/', 
            $this->image_path
        );
    }

    return null;
}

// Auto-categorize based on monument zone
public function getCategoryAttribute($value)
{
    if ($value) {
        return $value;
    }

    // Fallback to monument zone
    if ($this->monument) {
        return $this->monument->zone;
    }

    return 'General';
}
```

---

## 🏗️ 4. MODEL - Feedback.php

### 📊 Feedback Structure
```php
class Feedback extends Model
{
    protected $table = 'feedbacks';
    
    protected $fillable = [
        'name',
        'email',
        'message',
        'monument_id',
        'rating',        // 1-5 stars
        'status',        // pending, approved, rejected
    ];

    // Feedback belongs to Monument
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
```

### ⭐ Rating System Features
```php
// In Monument model - add computed attributes for ratings

// Get average rating
public function getAverageRatingAttribute()
{
    return $this->feedbacks()
                ->where('status', 'approved')
                ->avg('rating') ?: 0;
}

// Get total reviews count
public function getTotalReviewsAttribute()
{
    return $this->feedbacks()
                ->where('status', 'approved')
                ->count();
}

// Get rating distribution (1-5 stars count)
public function getRatingDistributionAttribute()
{
    $distribution = [];
    for ($i = 1; $i <= 5; $i++) {
        $distribution[$i] = $this->feedbacks()
                                 ->where('status', 'approved')
                                 ->where('rating', $i)
                                 ->count();
    }
    return $distribution;
}
```

---

## 🎮 5. CONTROLLER - Admin/MonumentController.php

### 📋 Index Method - Danh sách Monuments
```php
public function index(Request $request)
{
    $query = Monument::with(['creator', 'translations', 'gallery', 'feedbacks']);

    // 🔍 Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 Filter by zone
    if ($request->filled('zone')) {
        $query->where('zone', $request->zone);
    }

    // 🔍 Filter by world wonder status
    if ($request->filled('is_world_wonder')) {
        $query->where('is_world_wonder', $request->boolean('is_world_wonder'));
    }

    // 🔍 Search by title/location
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('location', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // 🔍 Filter by creator (for moderators)
    if (auth()->user()->role === 'moderator') {
        $query->where('created_by', auth()->id());
    }

    $monuments = $query->orderBy('created_at', 'desc')->paginate(10);

    // 📊 Calculate statistics
    $stats = [
        'total' => Monument::count(),
        'approved' => Monument::where('status', 'approved')->count(),
        'pending' => Monument::where('status', 'pending')->count(),
        'world_wonders' => Monument::where('is_world_wonder', true)->count(),
        'by_zone' => Monument::selectRaw('zone, COUNT(*) as count')
                            ->groupBy('zone')
                            ->pluck('count', 'zone')
                            ->toArray(),
    ];

    return view('admin.monuments.index', compact('monuments', 'stats'));
}
```

### ➕ Store Method - Tạo Monument mới
```php
public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'language' => 'required|in:en,vi',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'history' => 'nullable|string',
        'content' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'map_embed' => 'nullable|string',
        'zone' => 'nullable|in:North,South,Central,East,West',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'is_world_wonder' => 'boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'status' => 'required|in:draft,pending,approved',
        'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    // 📝 Create main monument
    $monumentData = [
        'title' => $request->title,
        'description' => $request->description,
        'history' => $request->history,
        'content' => $request->content,
        'location' => $request->location,
        'map_embed' => $request->map_embed,
        'zone' => $request->zone,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'is_world_wonder' => $request->boolean('is_world_wonder'),
        'status' => $request->status,
        'created_by' => auth()->id(),
    ];

    // 🖼️ Handle featured image upload
    if ($request->hasFile('image')) {
        try {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'), 
                'monuments'
            );

            if ($uploadResult['success']) {
                $monumentData['image'] = $uploadResult['url'];
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Featured image upload failed.');
        }
    }

    // 💾 Create monument
    $monument = Monument::create($monumentData);

    // 🌐 Create translation
    MonumentTranslation::create([
        'monument_id' => $monument->id,
        'language' => $request->language,
        'title' => $request->title,
        'description' => $request->description,
        'history' => $request->history,
        'content' => $request->content,
        'location' => $request->location,
    ]);

    // 📸 Handle gallery images
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $index => $image) {
            try {
                $uploadResult = $this->cloudinaryService->uploadImage($image, 'gallery');
                
                if ($uploadResult['success']) {
                    Gallery::create([
                        'monument_id' => $monument->id,
                        'title' => "Gallery Image " . ($index + 1),
                        'image_path' => $uploadResult['url'],
                        'category' => $request->zone ?? 'General',
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but continue with other images
                Log::error('Gallery image upload failed: ' . $e->getMessage());
            }
        }
    }

    return redirect()->route('admin.monuments.index')
                   ->with('success', 'Monument created successfully!');
}
```

### ✏️ Update Method - Cập nhật Monument
```php
public function update(Request $request, Monument $monument)
{
    // ✅ Validation (similar to store)
    $request->validate([
        'status' => 'required|in:draft,pending,approved',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'translations' => 'required|array',
        'translations.*.language' => 'required|in:vi,en',
        'translations.*.title' => 'required|string|max:255',
        // ... other validation rules
    ]);

    // 📝 Update main monument
    $monumentData = $request->only([
        'zone', 'latitude', 'longitude', 'is_world_wonder', 
        'map_embed', 'status'
    ]);

    // 🖼️ Handle featured image update
    if ($request->hasFile('image')) {
        try {
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'), 
                'monuments'
            );

            if ($uploadResult['success']) {
                $monumentData['image'] = $uploadResult['url'];
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Image upload failed.');
        }
    }

    $monument->update($monumentData);

    // 🌐 Update translations
    foreach ($request->translations as $translationData) {
        if (!empty($translationData['title'])) {
            MonumentTranslation::updateOrCreate(
                [
                    'monument_id' => $monument->id,
                    'language' => $translationData['language']
                ],
                [
                    'title' => $translationData['title'],
                    'description' => $translationData['description'],
                    'history' => $translationData['history'],
                    'content' => $translationData['content'],
                    'location' => $translationData['location'],
                ]
            );
        }
    }

    // 🔄 Update fallback fields from first translation
    $firstTranslation = $request->translations[0];
    $monument->update([
        'title' => $firstTranslation['title'],
        'description' => $firstTranslation['description'],
        'history' => $firstTranslation['history'],
        'content' => $firstTranslation['content'],
        'location' => $firstTranslation['location'],
    ]);

    return redirect()->route('admin.monuments.index')
                   ->with('success', 'Monument updated successfully!');
}
```

---

## 🌐 6. API ENDPOINTS

### 📋 Public API - Monuments List
```php
// GET /api/monuments
public function index(Request $request)
{
    $query = Monument::with(['gallery', 'feedbacks' => function($q) {
        $q->where('status', 'approved');
    }]);

    // 🔒 Public API - only approved monuments
    if (!$request->user()) {
        $query->approved();
    }

    // 🔍 Filters
    if ($request->filled('zone')) {
        $query->byZone($request->zone);
    }

    if ($request->filled('is_world_wonder')) {
        $query->worldWonders();
    }

    // 🗺️ Geographic search (within radius)
    if ($request->filled('lat') && $request->filled('lng') && $request->filled('radius')) {
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius; // in kilometers

        $query->whereRaw("
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude)))) <= ?
        ", [$lat, $lng, $lat, $radius]);
    }

    $monuments = $query->paginate($request->get('per_page', 12));

    // 🌐 Transform for API response
    $monuments->getCollection()->transform(function ($monument) use ($request) {
        $language = $request->get('language', 'vi');
        
        return [
            'id' => $monument->id,
            'title' => $monument->getTitle($language),
            'description' => $monument->getDescription($language),
            'location' => $monument->getLocation($language),
            'zone' => $monument->zone,
            'coordinates' => [
                'lat' => $monument->latitude,
                'lng' => $monument->longitude,
            ],
            'is_world_wonder' => $monument->is_world_wonder,
            'image_url' => $monument->image_url,
            'gallery_count' => $monument->gallery->count(),
            'average_rating' => round($monument->feedbacks->avg('rating') ?: 0, 1),
            'total_reviews' => $monument->feedbacks->count(),
            'created_at' => $monument->created_at,
        ];
    });

    return response()->json($monuments);
}
```

### 📱 Single Monument API
```php
// GET /api/monuments/{monument}
public function show(Request $request, Monument $monument)
{
    // 🔒 Check if monument is approved for public access
    if (!$request->user() && $monument->status !== 'approved') {
        return response()->json(['error' => 'Monument not found'], 404);
    }

    $monument->load([
        'gallery',
        'feedbacks' => function($q) {
            $q->where('status', 'approved')->latest();
        }
    ]);

    $language = $request->get('language', 'vi');

    return response()->json([
        'id' => $monument->id,
        'title' => $monument->getTitle($language),
        'description' => $monument->getDescription($language),
        'history' => $monument->getHistory($language),
        'content' => $monument->getContent($language),
        'location' => $monument->getLocation($language),
        'zone' => $monument->zone,
        'coordinates' => [
            'lat' => $monument->latitude,
            'lng' => $monument->longitude,
        ],
        'map_embed' => $monument->map_embed,
        'is_world_wonder' => $monument->is_world_wonder,
        'image_url' => $monument->image_url,
        'gallery' => $monument->gallery->map(function($image) {
            return [
                'id' => $image->id,
                'title' => $image->title,
                'image_url' => $image->image_url,
                'thumbnail_url' => $image->thumbnail_url,
                'blur_hash' => $image->blur_hash,
                'category' => $image->category,
            ];
        }),
        'reviews' => $monument->feedbacks->map(function($feedback) {
            return [
                'id' => $feedback->id,
                'name' => $feedback->name,
                'message' => $feedback->message,
                'rating' => $feedback->rating,
                'created_at' => $feedback->created_at->format('M d, Y'),
            ];
        }),
        'rating_summary' => [
            'average' => round($monument->feedbacks->avg('rating') ?: 0, 1),
            'total' => $monument->feedbacks->count(),
            'distribution' => [
                5 => $monument->feedbacks->where('rating', 5)->count(),
                4 => $monument->feedbacks->where('rating', 4)->count(),
                3 => $monument->feedbacks->where('rating', 3)->count(),
                2 => $monument->feedbacks->where('rating', 2)->count(),
                1 => $monument->feedbacks->where('rating', 1)->count(),
            ]
        ],
        'created_at' => $monument->created_at,
        'updated_at' => $monument->updated_at,
    ]);
}
```

---

## 🛣️ 7. ROUTES

### 🔐 Admin Routes
```php
// Admin Monuments Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'locale'])->group(function () {
    Route::resource('monuments', MonumentController::class);
    
    // Additional routes for gallery management
    Route::post('monuments/{monument}/gallery', [MonumentController::class, 'addGalleryImage'])
         ->name('monuments.gallery.add');
    Route::delete('gallery/{gallery}', [MonumentController::class, 'removeGalleryImage'])
         ->name('gallery.remove');
});
```

### 🌐 API Routes
```php
// Public API Routes
Route::prefix('api')->group(function () {
    // Public monuments (approved only)
    Route::get('/monuments', [Api\MonumentController::class, 'index']);
    Route::get('/monuments/{monument}', [Api\MonumentController::class, 'show']);
    
    // Gallery by zone/category
    Route::get('/gallery', [Api\GalleryController::class, 'index']);
    Route::get('/gallery/{category}', [Api\GalleryController::class, 'byCategory']);
    
    // Submit feedback (public)
    Route::post('/monuments/{monument}/feedback', [Api\FeedbackController::class, 'store']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/monuments', [Api\MonumentController::class, 'store']);
        Route::put('/monuments/{monument}', [Api\MonumentController::class, 'update']);
        Route::delete('/monuments/{monument}', [Api\MonumentController::class, 'destroy']);
    });
});
```

---

## 📊 8. TỔNG KẾT MONUMENTS MANAGEMENT

### 🎯 Key Features

#### ✅ **Geographic Integration**
- GPS coordinates storage and validation
- Zone-based categorization (North, South, Central, East, West)
- Google Maps embed support
- Radius-based search functionality
- Distance calculation between monuments

#### ✅ **Multimedia Management**
- Featured image with Cloudinary optimization
- Gallery system with multiple images per monument
- Progressive image loading with blur placeholders
- Thumbnail generation for performance
- Category-based image organization

#### ✅ **Review & Rating System**
- 5-star rating system
- Moderated feedback (pending/approved/rejected)
- Rating distribution analytics
- Average rating calculation
- Review display with user information

#### ✅ **Multilingual Content**
- Vietnamese and English translations
- Fallback content system
- Language-specific API responses
- Translation management interface

#### ✅ **World Heritage Integration**
- UNESCO World Wonder classification
- Special badges and highlighting
- Filtered views for world wonders
- Enhanced metadata for heritage sites

### 🔄 **Complete Workflow**

```
1. 👤 User creates monument → Status: Draft
2. 📝 Add translations, images, coordinates
3. 📸 Upload gallery images → Auto-optimized
4. 📍 Set GPS coordinates and zone
5. 🌍 Mark as world wonder (if applicable)
6. 📋 Submit for review → Status: Pending
7. 👨‍💼 Admin approves → Status: Approved
8. 🌐 Monument appears on public API/map
9. 👥 Visitors leave reviews and ratings
10. 📊 Analytics track popularity and ratings
```

### 🚀 **Technical Highlights**

- **Database Design**: Optimized for geographic queries with proper indexing
- **Image Processing**: Cloudinary integration with automatic optimization
- **API Design**: RESTful with geographic search capabilities
- **Performance**: Eager loading, pagination, thumbnail generation
- **Security**: Permission-based access, input validation, CSRF protection
- **UX**: Interactive maps, image galleries, rating displays

---

## 🎯 Next: Gallery & Feedback Systems

Phần tiếp theo sẽ cover chi tiết:
- **Gallery Management** - Advanced image handling
- **Feedback System** - Review moderation and analytics
- **API Integration** - Frontend consumption patterns
- **Performance Optimization** - Caching and CDN strategies
