<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monument;
use App\Models\MonumentTranslation;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MonumentController extends Controller
{
    private $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        $query = Monument::with(['creator', 'translations']);

        // Filter by status - only apply if status is provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by zone - only apply if zone is provided
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        // Search - works independently of filters
        // Search in both main table and translations table
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('history', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm)
                  ->orWhereHas('translations', function($tq) use ($searchTerm) {
                      $tq->where('title', 'like', $searchTerm)
                         ->orWhere('description', 'like', $searchTerm)
                         ->orWhere('history', 'like', $searchTerm)
                         ->orWhere('content', 'like', $searchTerm)
                         ->orWhere('location', 'like', $searchTerm);
                  });
            });
        }

        $monuments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.monuments.index', compact('monuments'));
    }

    public function create()
    {
        $zones = ['East', 'North', 'West', 'South', 'Central'];
        return view('admin.monuments.create_multilingual', compact('zones'));
    }

    public function store(Request $request)
    {
        Log::info('Monument store method called');
        Log::info('Request data:', $request->all());

        $request->validate([
            'language' => 'required|in:en,vi',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'content' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'map_embed' => 'nullable|string',
            'zone' => 'required|in:East,North,West,South,Central',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_world_wonder' => 'nullable|boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:draft,pending,approved',
        ]);

        try {
            // Create main monument record with fallback data
            $monumentData = [
                'title' => $request->title ?: 'Default Title',
                'location' => $request->location ?: 'Default Location',
                'map_embed' => $request->map_embed ?: null,
                'zone' => $request->zone ?: 'North',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_world_wonder' => $request->has('is_world_wonder') ? true : false,
                'content' => $request->content ?: 'Default Content',
                'description' => $request->description ?: 'Default Description',
                'history' => $request->history ?: null,
                'status' => $request->status ?: 'draft',
                'created_by' => auth()->id() ?: 1,
            ];

            Log::info('Monument data prepared:', $monumentData);

            // Upload featured image to Cloudinary
            if ($request->hasFile('featured_image')) {
                Log::info('Processing featured image...');
                $image = $request->file('featured_image');

                try {
                    $uploadResult = $this->cloudinaryService->uploadImage($image, 'monuments');

                    if ($uploadResult['success']) {
                        $monumentData['image'] = $uploadResult['url']; // Store full Cloudinary URL
                        Log::info('Image uploaded to Cloudinary:', ['url' => $uploadResult['url']]);
                    } else {
                        Log::error('Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                        return redirect()->back()->with('error', 'The featured image failed to upload: ' . $uploadResult['error']);
                    }
                } catch (\Exception $e) {
                    Log::error('Image upload exception:', ['error' => $e->getMessage()]);
                    return redirect()->back()->with('error', 'The featured image failed to upload.');
                }
            }

        // Create monument
        $monument = Monument::create($monumentData);

        // Create translation
        MonumentTranslation::create([
            'monument_id' => $monument->id,
            'language' => $request->language,
            'title' => $request->title,
            'description' => $request->description,
            'history' => $request->history,
            'content' => $request->content,
            'location' => $request->location,
        ]);

        // Handle gallery images with Cloudinary batch upload
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_filter($request->file('gallery_images'), function($file) {
                return $file && $file->isValid();
            });

            if (!empty($galleryFiles)) {
                Log::info('Processing gallery images:', ['count' => count($galleryFiles)]);

                // Batch upload to Cloudinary
                $batchResult = $this->cloudinaryService->uploadMultipleImages($galleryFiles, 'monuments');

                Log::info('Batch upload result:', [
                    'uploaded' => $batchResult['uploaded_count'],
                    'total' => $batchResult['total_count'],
                    'errors' => $batchResult['errors']
                ]);

                // Save successful uploads to gallery table
                foreach ($batchResult['results'] as $index => $result) {
                    $title = $request->input('image_captions.' . $index) ?: 'Gallery Image ' . ($index + 1);
                    $description = $request->input('image_captions.' . $index) ?: 'Additional monument image';

                    $galleryRecord = $monument->gallery()->create([
                        'title' => $title,
                        'image_path' => $result['url'], // Store full Cloudinary URL
                        'description' => $description,
                    ]);

                    Log::info('Gallery record created:', ['id' => $galleryRecord->id, 'url' => $result['url'], 'title' => $title]);
                }

                // Report any errors
                if (!empty($batchResult['errors'])) {
                    $errorMessage = 'Some gallery images failed to upload: ' . implode(', ', $batchResult['errors']);
                    Log::warning('Gallery upload errors:', $batchResult['errors']);
                    // Continue anyway - don't fail the whole monument creation
                }
            }
        }

            Log::info('Monument creation completed successfully:', ['monument_id' => $monument->id]);
            return redirect()->route('admin.monuments.show', $monument)->with('success', 'Monument created successfully with Cloudinary images!');

        } catch (\Exception $e) {
            Log::error('Error creating monument:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'An error occurred while creating the monument. Please try again.'])->withInput();
        }
    }

    public function show(Monument $monument)
    {
        $monument->load(['gallery', 'feedbacks']);
        return view('admin.monuments.show', compact('monument'));
    }

    public function edit(Monument $monument)
    {
        $monument->load('translations');
        $zones = ['East', 'North', 'West', 'South', 'Central'];
        return view('admin.monuments.edit_multilingual', compact('monument', 'zones'));
    }

    public function update(Request $request, Monument $monument)
    {
        // Validate the new form structure
        $rules = [
            'zone' => 'required|in:East,North,West,South,Central',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_world_wonder' => 'nullable|boolean',
            'status' => 'required|in:draft,pending,approved',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'map_embed' => 'nullable|string',
            // Validate translations
            'translations' => 'required|array',
            'translations.*.language' => 'required|in:en,vi',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.history' => 'nullable|string',
            'translations.*.content' => 'nullable|string',
            'translations.*.location' => 'nullable|string|max:255',
        ];

        $request->validate($rules);

        // Get Vietnamese translation (default language) for base monument data
        $viTranslation = collect($request->translations)->firstWhere('language', 'vi');

        if (!$viTranslation) {
            return redirect()->back()->with('error', 'Vietnamese translation is required as the default language.');
        }

        $data = [
            'title' => $viTranslation['title'],
            'description' => $viTranslation['description'] ?? null,
            'history' => $viTranslation['history'] ?? null,
            'location' => $viTranslation['location'] ?? null,
            'content' => $viTranslation['content'] ?? null,
            'map_embed' => $request->map_embed,
            'zone' => $request->zone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_world_wonder' => $request->has('is_world_wonder') ? true : false,
            'status' => $request->status,
        ];

        // Handle featured image with Cloudinary
        if ($request->hasFile('featured_image')) {
            try {
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('featured_image'), 'monuments');

                if ($uploadResult['success']) {
                    $data['image'] = $uploadResult['url'];
                    Log::info('Featured image updated via Cloudinary:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return redirect()->back()->with('error', 'Featured image upload failed: ' . $uploadResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Featured image upload exception:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Featured image upload failed.');
            }
        }

        // Handle gallery images with Cloudinary
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_filter($request->file('gallery_images'), function($file) {
                return $file && $file->isValid();
            });

            if (!empty($galleryFiles)) {
                Log::info('Processing gallery images for edit:', ['count' => count($galleryFiles)]);

                try {
                    $batchResult = $this->cloudinaryService->uploadMultipleImages($galleryFiles, 'gallery');

                    if ($batchResult['success'] && !empty($batchResult['urls'])) {
                        // Create gallery records for successful uploads
                        foreach ($batchResult['urls'] as $index => $url) {
                            $monument->gallery()->create([
                                'title' => 'Gallery Image ' . ($monument->gallery()->count() + $index + 1),
                                'image_path' => $url, // Store full Cloudinary URL
                                'description' => '',
                            ]);
                        }

                        Log::info('Gallery images added:', ['count' => count($batchResult['urls'])]);
                    }

                    // Report any errors
                    if (!empty($batchResult['errors'])) {
                        Log::warning('Some gallery images failed:', $batchResult['errors']);
                        // Continue anyway - don't fail the whole update
                    }
                } catch (\Exception $e) {
                    Log::error('Gallery upload exception:', ['error' => $e->getMessage()]);
                    // Continue anyway - don't fail the whole update
                }
            }
        }

        $monument->update($data);

        // Handle translations (save/update English translation if provided)
        foreach ($request->translations as $translationData) {
            // Skip Vietnamese as it's already in base monument data
            if ($translationData['language'] === 'vi') {
                continue;
            }

            // Update or create translation
            $monument->translations()->updateOrCreate(
                [
                    'monument_id' => $monument->id,
                    'language' => $translationData['language'],
                ],
                [
                    'title' => $translationData['title'],
                    'description' => $translationData['description'] ?? null,
                    'history' => $translationData['history'] ?? null,
                    'content' => $translationData['content'] ?? null,
                    'location' => $translationData['location'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.monuments.show', $monument)->with('success', 'Monument updated successfully!');
    }

    public function destroy(Monument $monument)
    {
        $user = auth()->user();

        // Check permissions: Admin can delete anything, Moderator can only delete non-approved content
        if ($user->role === 'moderator' && $monument->status === 'approved') {
            abort(403, 'Cannot delete approved content. Only administrators can delete approved content.');
        }

        if ($monument->image) {
            Storage::disk('public')->delete($monument->image);
        }

        // Delete gallery images
        foreach ($monument->gallery as $gallery) {
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
        }

        $monument->delete();

        return redirect()->route('admin.monuments.index')->with('success', 'Monument deleted successfully!');
    }

    public function approve(Monument $monument)
    {
        $user = auth()->user();

        // Only admin can approve content
        if ($user->role !== 'admin') {
            abort(403, 'Only administrators can approve content.');
        }

        $monument->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Monument approved successfully!');
    }
}
