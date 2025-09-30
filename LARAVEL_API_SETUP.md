# Laravel API Setup for Frontend Integration

## Overview

This guide explains how to set up the Laravel backend API to work with the React frontend.

---

## 1. Enable CORS

### Install Laravel CORS Package

The package should already be installed, but if not:

```bash
composer require fruitcake/laravel-cors
```

### Configure CORS

Edit `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        'http://localhost:3000',  // React dev server
        'http://127.0.0.1:3000',
        // Add production URLs here
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];
```

---

## 2. Create Public API Routes

Create `routes/api_public.php` or add to `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MonumentController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\FeedbackController;

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Monuments
    Route::get('/monuments', [MonumentController::class, 'index']);
    Route::get('/monuments/{id}', [MonumentController::class, 'show']);
    Route::get('/monuments/zone/{zone}', [MonumentController::class, 'byZone']);
    
    // Gallery
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::get('/gallery/{id}', [GalleryController::class, 'show']);
    Route::get('/gallery/monument/{monumentId}', [GalleryController::class, 'byMonument']);
    
    // Feedback (public submission)
    Route::post('/feedback', [FeedbackController::class, 'store']);
    
    // Contact
    Route::post('/contact', [ContactController::class, 'send']);
});
```

---

## 3. Create API Controllers

### MonumentController (API)

Create `app/Http/Controllers/API/MonumentController.php`:

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Monument;
use Illuminate\Http\Request;

class MonumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Monument::with(['translations', 'creator'])
            ->where('status', 'approved');
        
        // Filter by zone
        if ($request->has('zone')) {
            $query->where('zone', $request->zone);
        }
        
        // Search
        if ($request->has('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $monuments = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $monuments->map(function($monument) {
                return [
                    'id' => $monument->id,
                    'title' => $monument->title,
                    'description' => $monument->description,
                    'zone' => $monument->zone,
                    'latitude' => $monument->latitude,
                    'longitude' => $monument->longitude,
                    'image' => $monument->image_url,
                    'history' => $monument->history,
                    'created_at' => $monument->created_at->format('Y-m-d'),
                    'creator' => [
                        'name' => $monument->creator?->name,
                        'avatar' => $monument->creator?->avatar_url,
                    ],
                ];
            }),
        ]);
    }
    
    public function show($id)
    {
        $monument = Monument::with(['translations', 'creator', 'galleries'])
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $monument->id,
                'title' => $monument->title,
                'description' => $monument->description,
                'zone' => $monument->zone,
                'latitude' => $monument->latitude,
                'longitude' => $monument->longitude,
                'image' => $monument->image_url,
                'history' => $monument->history,
                'galleries' => $monument->galleries->map(function($gallery) {
                    return [
                        'id' => $gallery->id,
                        'image' => $gallery->image_url,
                        'title' => $gallery->title,
                    ];
                }),
                'created_at' => $monument->created_at->format('Y-m-d'),
            ],
        ]);
    }
    
    public function byZone($zone)
    {
        $monuments = Monument::with(['translations', 'creator'])
            ->where('zone', $zone)
            ->where('status', 'approved')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $monuments,
        ]);
    }
}
```

### GalleryController (API)

Create `app/Http/Controllers/API/GalleryController.php`:

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with(['monument']);
        
        // Filter by monument
        if ($request->has('monument_id')) {
            $query->where('monument_id', $request->monument_id);
        }
        
        // Filter by category (if you have categories)
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $galleries = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $galleries->map(function($gallery) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'image' => $gallery->image_url,
                    'thumbnail' => $gallery->image_url, // Or create thumbnail
                    'monument' => [
                        'id' => $gallery->monument->id,
                        'title' => $gallery->monument->title,
                    ],
                    'category' => $gallery->category ?? 'Monuments',
                ];
            }),
        ]);
    }
    
    public function show($id)
    {
        $gallery = Gallery::with(['monument'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $gallery,
        ]);
    }
    
    public function byMonument($monumentId)
    {
        $galleries = Gallery::where('monument_id', $monumentId)->get();
        
        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }
}
```

### FeedbackController (API)

Create `app/Http/Controllers/API/FeedbackController.php`:

```php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'monument_id' => 'nullable|exists:monuments,id',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
        ]);
        
        $feedback = Feedback::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully!',
            'data' => $feedback,
        ], 201);
    }
}
```

---

## 4. Update Models

### Add Image URL Accessor to Monument Model

```php
// app/Models/Monument.php

public function getImageUrlAttribute()
{
    if ($this->image) {
        // If Cloudinary URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        // If local storage
        return asset('storage/' . $this->image);
    }
    return asset('images/default-monument.jpg');
}
```

### Add Category to Gallery (Optional)

If you want to add categories to gallery:

```bash
php artisan make:migration add_category_to_gallery_table
```

```php
public function up()
{
    Schema::table('gallery', function (Blueprint $table) {
        $table->string('category')->default('Monuments')->after('description');
    });
}
```

---

## 5. Test API Endpoints

### Using Postman or cURL

```bash
# Get all monuments
curl http://localhost:8000/api/v1/monuments

# Get monuments by zone
curl http://localhost:8000/api/v1/monuments?zone=East

# Get single monument
curl http://localhost:8000/api/v1/monuments/1

# Get gallery
curl http://localhost:8000/api/v1/gallery

# Submit feedback
curl -X POST http://localhost:8000/api/v1/feedback \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "monument_id": 1,
    "rating": 5,
    "message": "Great monument!"
  }'
```

---

## 6. Update .env

```env
# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000

# Frontend URL
FRONTEND_URL=http://localhost:3000
```

---

## 7. Seed Sample Data (Optional)

Create a seeder for testing:

```bash
php artisan make:seeder PublicDataSeeder
```

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monument;
use App\Models\Gallery;

class PublicDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample monuments
        $monuments = [
            [
                'title' => 'Taj Mahal',
                'description' => 'An ivory-white marble mausoleum',
                'zone' => 'South',
                'latitude' => 27.1751,
                'longitude' => 78.0421,
                'status' => 'approved',
            ],
            // Add more...
        ];
        
        foreach ($monuments as $data) {
            Monument::create($data);
        }
    }
}
```

Run seeder:
```bash
php artisan db:seed --class=PublicDataSeeder
```

---

## 8. API Response Format

All API responses follow this format:

```json
{
  "success": true,
  "data": [...],
  "message": "Optional message"
}
```

Error responses:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error"]
  }
}
```

---

## 9. Rate Limiting (Optional)

Add rate limiting to prevent abuse:

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'api' => [
        'throttle:60,1', // 60 requests per minute
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

---

## 10. Checklist

- [ ] CORS configured
- [ ] API routes created
- [ ] Controllers implemented
- [ ] Models updated with accessors
- [ ] API tested with Postman/cURL
- [ ] Sample data seeded
- [ ] Error handling implemented
- [ ] Rate limiting configured
- [ ] Documentation updated

---

## Troubleshooting

### CORS Error
- Check `config/cors.php` settings
- Verify frontend URL in allowed_origins
- Clear config cache: `php artisan config:clear`

### 404 Not Found
- Check route names
- Run `php artisan route:list`
- Verify API prefix

### 500 Internal Server Error
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode in `.env`: `APP_DEBUG=true`

---

## Next Steps

1. Start Laravel server: `php artisan serve`
2. Start React frontend: `cd frontend && npm start`
3. Test integration
4. Deploy to production

---

**API Base URL**: `http://localhost:8000/api/v1`  
**Frontend URL**: `http://localhost:3000`  
**Status**: Ready for Integration

