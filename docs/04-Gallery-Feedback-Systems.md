# 📸 Gallery & Feedback Systems - Hướng dẫn chi tiết

## 🏗️ Tổng quan kiến trúc

Hệ thống Gallery và Feedback quản lý hình ảnh và đánh giá cho monuments với tối ưu hóa hiệu suất và trải nghiệm người dùng:

- **Gallery System**: Upload, optimize, categorize images với Cloudinary
- **Feedback System**: Reviews, ratings, moderation workflow
- **Performance**: Progressive loading, thumbnails, caching
- **Analytics**: Rating statistics, feedback trends

---

## 📸 1. GALLERY SYSTEM

### 🏗️ Gallery Model Deep Dive

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

    // Auto-append computed attributes for API responses
    protected $appends = ['image_url', 'thumbnail_url', 'blur_hash'];
}
```

### 🖼️ Advanced Image Processing

#### **Full-size Image URL**
```php
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
```

#### **Optimized Thumbnails**
```php
public function getThumbnailUrlAttribute()
{
    if (!$this->image_path) {
        return null;
    }

    // Cloudinary - add transformation parameters
    if (filter_var($this->image_path, FILTER_VALIDATE_URL) && 
        str_contains($this->image_path, 'cloudinary')) {
        
        // Transform: 400x300, crop fill, auto quality, auto format
        return str_replace(
            '/upload/', 
            '/upload/w_400,h_300,c_fill,q_auto,f_auto/', 
            $this->image_path
        );
    }

    // Local storage fallback
    return $this->image_url;
}
```

#### **Progressive Loading Placeholders**
```php
public function getBlurHashAttribute()
{
    if (!$this->image_path) {
        return null;
    }

    // Cloudinary - create tiny blur placeholder (20x15)
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
```

#### **Smart Categorization**
```php
public function getCategoryAttribute($value)
{
    // Use explicit category if set
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

### 🎮 Gallery Controller

#### **Index - Gallery Management**
```php
public function index(Request $request)
{
    $query = Gallery::with('monument');

    // 🔍 Filter by monument
    if ($request->filled('monument_id')) {
        $query->where('monument_id', $request->monument_id);
    }

    // 🔍 Search by title/description
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // 🔍 Filter by category
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    $galleries = $query->orderBy('created_at', 'desc')->paginate(12);
    $monuments = Monument::approved()->get();

    // 📊 Calculate statistics
    $stats = [
        'total_images' => Gallery::count(),
        'by_category' => Gallery::selectRaw('category, COUNT(*) as count')
                              ->groupBy('category')
                              ->pluck('count', 'category')
                              ->toArray(),
        'recent_uploads' => Gallery::where('created_at', '>=', now()->subDays(7))->count(),
    ];

    return view('admin.gallery.index', compact('galleries', 'monuments', 'stats'));
}
```

#### **Store - Upload New Image**
```php
public function store(Request $request)
{
    // ✅ Validation
    $request->validate([
        'monument_id' => 'required|exists:monuments,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:100',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
    ]);

    $data = $request->only(['monument_id', 'title', 'description', 'category']);

    // 🖼️ Handle image upload
    if ($request->hasFile('image')) {
        Log::info('Uploading gallery image:', [
            'monument_id' => $request->monument_id,
            'file_name' => $request->file('image')->getClientOriginalName(),
            'file_size' => $request->file('image')->getSize()
        ]);

        try {
            // Upload to Cloudinary with optimizations
            $uploadResult = $this->cloudinaryService->uploadImage(
                $request->file('image'), 
                'gallery',
                [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'flags' => 'progressive',
                ]
            );

            if ($uploadResult['success']) {
                $data['image_path'] = $uploadResult['url'];
                Log::info('Gallery image uploaded successfully:', ['url' => $uploadResult['url']]);
            } else {
                Log::error('Gallery upload failed:', ['error' => $uploadResult['error']]);
                return redirect()->back()
                    ->with('error', 'Image upload failed: ' . $uploadResult['error']);
            }
        } catch (\Exception $e) {
            Log::error('Gallery upload exception:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Image upload failed.');
        }
    }

    // 💾 Create gallery entry
    $gallery = Gallery::create($data);

    return redirect()->route('admin.gallery.show', $gallery)
                   ->with('success', 'Gallery image added successfully!');
}
```

#### **Bulk Upload Support**
```php
public function bulkStore(Request $request)
{
    $request->validate([
        'monument_id' => 'required|exists:monuments,id',
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        'category' => 'nullable|string|max:100',
    ]);

    $uploaded = 0;
    $failed = 0;

    foreach ($request->file('images') as $index => $image) {
        try {
            $uploadResult = $this->cloudinaryService->uploadImage($image, 'gallery');
            
            if ($uploadResult['success']) {
                Gallery::create([
                    'monument_id' => $request->monument_id,
                    'title' => "Gallery Image " . ($index + 1),
                    'image_path' => $uploadResult['url'],
                    'category' => $request->category,
                ]);
                $uploaded++;
            } else {
                $failed++;
            }
        } catch (\Exception $e) {
            Log::error('Bulk upload failed for image ' . $index, ['error' => $e->getMessage()]);
            $failed++;
        }
    }

    $message = "Uploaded {$uploaded} images successfully.";
    if ($failed > 0) {
        $message .= " {$failed} images failed to upload.";
    }

    return redirect()->route('admin.gallery.index')->with('success', $message);
}
```

### 🌐 Gallery API Endpoints

#### **Public Gallery API**
```php
// GET /api/gallery
public function index(Request $request)
{
    $query = Gallery::with('monument');

    // 🔍 Filter by category/zone
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // 🔍 Filter by monument
    if ($request->filled('monument_id')) {
        $query->where('monument_id', $request->monument_id);
    }

    // 🔍 Search
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    $galleries = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 20));

    // 🎨 Transform for frontend
    $galleries->getCollection()->transform(function ($gallery) {
        return [
            'id' => $gallery->id,
            'title' => $gallery->title,
            'description' => $gallery->description,
            'category' => $gallery->category,
            'image_url' => $gallery->image_url,
            'thumbnail_url' => $gallery->thumbnail_url,
            'blur_hash' => $gallery->blur_hash,
            'monument' => [
                'id' => $gallery->monument->id,
                'title' => $gallery->monument->title,
                'zone' => $gallery->monument->zone,
            ],
            'created_at' => $gallery->created_at,
        ];
    });

    return response()->json($galleries);
}
```

#### **Gallery by Category**
```php
// GET /api/gallery/categories
public function categories()
{
    $categories = Gallery::selectRaw('category, COUNT(*) as count')
                         ->groupBy('category')
                         ->orderBy('count', 'desc')
                         ->get();

    return response()->json($categories);
}

// GET /api/gallery/category/{category}
public function byCategory(Request $request, $category)
{
    $galleries = Gallery::where('category', $category)
                        ->with('monument')
                        ->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 20));

    return response()->json($galleries);
}
```

---

## ⭐ 2. FEEDBACK SYSTEM

### 🏗️ Feedback Model

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

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
    ];

    // Feedback belongs to Monument
    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}
```

### 📊 Rating Analytics (Add to Monument Model)

```php
// In Monument.php - add these computed attributes

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

// Get recent reviews (last 10)
public function getRecentReviewsAttribute()
{
    return $this->feedbacks()
                ->where('status', 'approved')
                ->latest()
                ->limit(10)
                ->get();
}
```

### 🎮 Feedback Controller

#### **Index - Feedback Management**
```php
public function index(Request $request)
{
    $query = Feedback::with('monument');

    // 🔍 Filter by monument
    if ($request->filled('monument_id')) {
        $query->where('monument_id', $request->monument_id);
    }

    // 🔍 Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔍 Filter by rating
    if ($request->filled('rating')) {
        $query->where('rating', $request->rating);
    }

    // 🔍 Filter by date range
    if ($request->filled('days')) {
        $query->where('created_at', '>=', now()->subDays($request->days));
    }

    // 🔍 Search
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('message', 'like', '%' . $request->search . '%');
        });
    }

    $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);
    $monuments = Monument::approved()->get();

    // 📊 Calculate comprehensive stats
    $stats = [
        'total' => Feedback::count(),
        'pending' => Feedback::where('status', 'pending')->count(),
        'approved' => Feedback::where('status', 'approved')->count(),
        'rejected' => Feedback::where('status', 'rejected')->count(),
        'today' => Feedback::whereDate('created_at', today())->count(),
        'this_week' => Feedback::where('created_at', '>=', now()->startOfWeek())->count(),
        'this_month' => Feedback::where('created_at', '>=', now()->startOfMonth())->count(),
        'average_rating' => round(Feedback::where('status', 'approved')->avg('rating') ?: 0, 1),
        'rating_distribution' => [
            5 => Feedback::where('status', 'approved')->where('rating', 5)->count(),
            4 => Feedback::where('status', 'approved')->where('rating', 4)->count(),
            3 => Feedback::where('status', 'approved')->where('rating', 3)->count(),
            2 => Feedback::where('status', 'approved')->where('rating', 2)->count(),
            1 => Feedback::where('status', 'approved')->where('rating', 1)->count(),
        ]
    ];

    return view('admin.feedbacks.index', compact('feedbacks', 'monuments', 'stats'));
}
```

#### **Moderation Actions**
```php
public function approve(Feedback $feedback)
{
    $feedback->update(['status' => 'approved']);
    
    return redirect()->back()->with('success', 'Feedback approved successfully!');
}

public function reject(Feedback $feedback)
{
    $feedback->update(['status' => 'rejected']);
    
    return redirect()->back()->with('success', 'Feedback rejected successfully!');
}

public function bulkAction(Request $request)
{
    $request->validate([
        'action' => 'required|in:approve,reject,delete',
        'feedback_ids' => 'required|array',
        'feedback_ids.*' => 'exists:feedbacks,id',
    ]);

    $feedbacks = Feedback::whereIn('id', $request->feedback_ids);

    switch ($request->action) {
        case 'approve':
            $feedbacks->update(['status' => 'approved']);
            $message = 'Selected feedbacks approved successfully!';
            break;
        case 'reject':
            $feedbacks->update(['status' => 'rejected']);
            $message = 'Selected feedbacks rejected successfully!';
            break;
        case 'delete':
            $feedbacks->delete();
            $message = 'Selected feedbacks deleted successfully!';
            break;
    }

    return redirect()->back()->with('success', $message);
}
```

### 🌐 Feedback API Endpoints

#### **Submit Feedback (Public)**
```php
// POST /api/monuments/{monument}/feedback
public function store(Request $request, Monument $monument)
{
    // ✅ Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|min:10|max:1000',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // 🔒 Rate limiting (prevent spam)
    $existingFeedback = Feedback::where('email', $request->email)
                               ->where('monument_id', $monument->id)
                               ->where('created_at', '>=', now()->subHours(24))
                               ->first();

    if ($existingFeedback) {
        return response()->json([
            'error' => 'You can only submit one review per monument per day.'
        ], 429);
    }

    // 💾 Create feedback
    $feedback = Feedback::create([
        'monument_id' => $monument->id,
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
        'rating' => $request->rating,
        'status' => 'pending', // Requires moderation
    ]);

    return response()->json([
        'message' => 'Thank you for your feedback! It will be reviewed before being published.',
        'feedback' => [
            'id' => $feedback->id,
            'name' => $feedback->name,
            'message' => $feedback->message,
            'rating' => $feedback->rating,
            'status' => $feedback->status,
            'created_at' => $feedback->created_at,
        ]
    ], 201);
}
```

#### **Get Monument Reviews**
```php
// GET /api/monuments/{monument}/reviews
public function reviews(Request $request, Monument $monument)
{
    $reviews = $monument->feedbacks()
                       ->where('status', 'approved')
                       ->orderBy('created_at', 'desc')
                       ->paginate($request->get('per_page', 10));

    // 📊 Include rating summary
    $ratingSummary = [
        'average' => round($monument->average_rating, 1),
        'total' => $monument->total_reviews,
        'distribution' => $monument->rating_distribution,
    ];

    return response()->json([
        'reviews' => $reviews,
        'rating_summary' => $ratingSummary,
    ]);
}
```

---

## 🎨 3. FRONTEND INTEGRATION

### 📱 React Gallery Component Example

```jsx
// GalleryGrid.jsx
import React, { useState, useEffect } from 'react';

const GalleryGrid = ({ monumentId, category }) => {
    const [images, setImages] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedImage, setSelectedImage] = useState(null);

    useEffect(() => {
        fetchGallery();
    }, [monumentId, category]);

    const fetchGallery = async () => {
        try {
            const params = new URLSearchParams();
            if (monumentId) params.append('monument_id', monumentId);
            if (category) params.append('category', category);

            const response = await fetch(`/api/gallery?${params}`);
            const data = await response.json();
            setImages(data.data);
        } catch (error) {
            console.error('Failed to fetch gallery:', error);
        } finally {
            setLoading(false);
        }
    };

    const ImageCard = ({ image }) => {
        const [imageLoaded, setImageLoaded] = useState(false);

        return (
            <div className="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow">
                {/* Blur placeholder */}
                {!imageLoaded && image.blur_hash && (
                    <img
                        src={image.blur_hash}
                        alt=""
                        className="absolute inset-0 w-full h-full object-cover filter blur-sm"
                    />
                )}
                
                {/* Main image */}
                <img
                    src={image.thumbnail_url}
                    alt={image.title}
                    className={`w-full h-64 object-cover cursor-pointer transition-opacity ${
                        imageLoaded ? 'opacity-100' : 'opacity-0'
                    }`}
                    onLoad={() => setImageLoaded(true)}
                    onClick={() => setSelectedImage(image)}
                />
                
                {/* Overlay */}
                <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <h3 className="text-white font-semibold">{image.title}</h3>
                    <p className="text-white/80 text-sm">{image.monument.title}</p>
                </div>
            </div>
        );
    };

    if (loading) {
        return <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            {[...Array(8)].map((_, i) => (
                <div key={i} className="bg-gray-200 animate-pulse h-64 rounded-lg"></div>
            ))}
        </div>;
    }

    return (
        <>
            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                {images.map(image => (
                    <ImageCard key={image.id} image={image} />
                ))}
            </div>

            {/* Lightbox Modal */}
            {selectedImage && (
                <div className="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
                    <div className="relative max-w-4xl max-h-full">
                        <img
                            src={selectedImage.image_url}
                            alt={selectedImage.title}
                            className="max-w-full max-h-full object-contain"
                        />
                        <button
                            onClick={() => setSelectedImage(null)}
                            className="absolute top-4 right-4 text-white text-2xl"
                        >
                            ×
                        </button>
                    </div>
                </div>
            )}
        </>
    );
};

export default GalleryGrid;
```

### ⭐ React Rating Component

```jsx
// RatingSystem.jsx
import React, { useState } from 'react';

const RatingSystem = ({ monumentId, currentRating, totalReviews, onReviewSubmit }) => {
    const [showForm, setShowForm] = useState(false);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        message: '',
        rating: 5
    });
    const [submitting, setSubmitting] = useState(false);

    const submitReview = async (e) => {
        e.preventDefault();
        setSubmitting(true);

        try {
            const response = await fetch(`/api/monuments/${monumentId}/feedback`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                setShowForm(false);
                setFormData({ name: '', email: '', message: '', rating: 5 });
                onReviewSubmit?.();
                alert('Thank you for your review! It will be published after moderation.');
            } else {
                const error = await response.json();
                alert(error.error || 'Failed to submit review');
            }
        } catch (error) {
            alert('Failed to submit review');
        } finally {
            setSubmitting(false);
        }
    };

    const StarRating = ({ rating, onRatingChange, readonly = false }) => {
        return (
            <div className="flex">
                {[1, 2, 3, 4, 5].map(star => (
                    <button
                        key={star}
                        type="button"
                        className={`text-2xl ${
                            star <= rating ? 'text-yellow-400' : 'text-gray-300'
                        } ${!readonly ? 'hover:text-yellow-400 cursor-pointer' : ''}`}
                        onClick={() => !readonly && onRatingChange?.(star)}
                        disabled={readonly}
                    >
                        ★
                    </button>
                ))}
            </div>
        );
    };

    return (
        <div className="bg-white rounded-lg shadow-md p-6">
            <div className="flex items-center justify-between mb-4">
                <div>
                    <div className="flex items-center gap-2">
                        <StarRating rating={Math.round(currentRating)} readonly />
                        <span className="text-lg font-semibold">{currentRating.toFixed(1)}</span>
                        <span className="text-gray-600">({totalReviews} reviews)</span>
                    </div>
                </div>
                <button
                    onClick={() => setShowForm(true)}
                    className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                >
                    Write Review
                </button>
            </div>

            {showForm && (
                <form onSubmit={submitReview} className="border-t pt-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <input
                            type="text"
                            placeholder="Your Name"
                            value={formData.name}
                            onChange={(e) => setFormData({...formData, name: e.target.value})}
                            className="border rounded-lg px-3 py-2"
                            required
                        />
                        <input
                            type="email"
                            placeholder="Your Email"
                            value={formData.email}
                            onChange={(e) => setFormData({...formData, email: e.target.value})}
                            className="border rounded-lg px-3 py-2"
                            required
                        />
                    </div>
                    
                    <div className="mb-4">
                        <label className="block text-sm font-medium mb-2">Rating</label>
                        <StarRating 
                            rating={formData.rating}
                            onRatingChange={(rating) => setFormData({...formData, rating})}
                        />
                    </div>

                    <textarea
                        placeholder="Write your review..."
                        value={formData.message}
                        onChange={(e) => setFormData({...formData, message: e.target.value})}
                        className="w-full border rounded-lg px-3 py-2 h-32 mb-4"
                        required
                    />

                    <div className="flex gap-2">
                        <button
                            type="submit"
                            disabled={submitting}
                            className="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50"
                        >
                            {submitting ? 'Submitting...' : 'Submit Review'}
                        </button>
                        <button
                            type="button"
                            onClick={() => setShowForm(false)}
                            className="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            )}
        </div>
    );
};

export default RatingSystem;
```

---

## 📊 4. PERFORMANCE OPTIMIZATION

### 🚀 Image Optimization Strategies

#### **Cloudinary Transformations**
```php
// In CloudinaryService.php
public function getOptimizedUrl($imageUrl, $options = [])
{
    if (!str_contains($imageUrl, 'cloudinary')) {
        return $imageUrl;
    }

    $transformations = [];
    
    // Quality optimization
    $transformations[] = 'q_auto';
    $transformations[] = 'f_auto';
    
    // Responsive sizing
    if (isset($options['width'])) {
        $transformations[] = "w_{$options['width']}";
    }
    if (isset($options['height'])) {
        $transformations[] = "h_{$options['height']}";
    }
    
    // Crop mode
    $transformations[] = $options['crop'] ?? 'c_fill';
    
    // Progressive loading
    $transformations[] = 'fl_progressive';
    
    $transformString = implode(',', $transformations);
    
    return str_replace('/upload/', "/upload/{$transformString}/", $imageUrl);
}
```

#### **Lazy Loading Implementation**
```php
// Add to Gallery model
public function getResponsiveUrlsAttribute()
{
    if (!$this->image_path || !str_contains($this->image_path, 'cloudinary')) {
        return null;
    }

    return [
        'thumbnail' => $this->getOptimizedUrl(['width' => 400, 'height' => 300]),
        'medium' => $this->getOptimizedUrl(['width' => 800, 'height' => 600]),
        'large' => $this->getOptimizedUrl(['width' => 1200, 'height' => 900]),
        'blur' => $this->blur_hash,
    ];
}
```

### 📈 Caching Strategies

#### **Gallery Caching**
```php
// In GalleryController
public function index(Request $request)
{
    $cacheKey = 'gallery_' . md5(serialize($request->all()));
    
    $galleries = Cache::remember($cacheKey, 3600, function() use ($request) {
        // ... existing query logic
        return $query->paginate(12);
    });

    return response()->json($galleries);
}
```

#### **Rating Statistics Caching**
```php
// In Monument model
public function getAverageRatingAttribute()
{
    return Cache::remember("monument_{$this->id}_avg_rating", 1800, function() {
        return $this->feedbacks()
                    ->where('status', 'approved')
                    ->avg('rating') ?: 0;
    });
}
```

---

## 📊 5. TỔNG KẾT GALLERY & FEEDBACK

### 🎯 Key Features

#### ✅ **Gallery System**
- **Multi-format Support**: JPEG, PNG, GIF, WebP
- **Cloud Integration**: Cloudinary with auto-optimization
- **Progressive Loading**: Blur placeholders for smooth UX
- **Responsive Images**: Multiple sizes for different devices
- **Bulk Upload**: Multiple images at once
- **Smart Categorization**: Auto-categorize by monument zone

#### ✅ **Feedback System**
- **5-Star Rating**: Comprehensive rating system
- **Moderation Workflow**: Pending → Approved/Rejected
- **Spam Protection**: Rate limiting, duplicate prevention
- **Analytics Dashboard**: Rating distribution, trends
- **Bulk Actions**: Approve/reject multiple reviews
- **Email Notifications**: (Can be implemented)

#### ✅ **Performance Features**
- **Image Optimization**: Automatic compression and format conversion
- **Caching**: Redis/database caching for frequently accessed data
- **CDN Integration**: Global content delivery
- **Lazy Loading**: Progressive image loading
- **API Pagination**: Efficient data loading

### 🔄 **Complete User Journey**

```
Gallery Flow:
1. 👤 Admin uploads images → Cloudinary optimization
2. 🖼️ Images processed → Thumbnails, blur placeholders generated
3. 📱 Frontend requests gallery → API returns optimized URLs
4. 🎨 Progressive loading → Blur → Thumbnail → Full image
5. 👁️ User views gallery → Smooth, fast experience

Feedback Flow:
1. 👤 User visits monument → Sees current ratings
2. ⭐ User submits review → Validation, spam check
3. 📋 Review enters moderation → Admin notification
4. 👨‍💼 Admin approves → Review goes live
5. 📊 Statistics update → Average rating recalculated
6. 🔄 Cache invalidated → Fresh data for next visitors
```

### 🚀 **Technical Highlights**

- **Database Design**: Optimized indexes for fast queries
- **API Design**: RESTful with proper pagination and filtering
- **Image Processing**: Advanced Cloudinary transformations
- **Caching Strategy**: Multi-layer caching for performance
- **Security**: Input validation, rate limiting, CSRF protection
- **Scalability**: CDN integration, database optimization

---

## 🎯 Next: API Documentation & Frontend Integration

Phần cuối sẽ cover:
- **Complete API Documentation** - All endpoints with examples
- **Frontend Integration Patterns** - React components and hooks
- **Performance Monitoring** - Analytics and optimization
- **Deployment Guide** - Production setup and scaling
