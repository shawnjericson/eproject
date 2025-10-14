<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Monument;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MonumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Monument::with(['creator:id,name', 'translations']);

        // For public API, only show approved monuments
        if (!$request->user() || !in_array($request->user()->role, ['admin', 'moderator'])) {
            $query->approved();
        } else {
            // For admin/moderator, allow filtering by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        // Filter by zone - only apply if zone is provided
        if ($request->filled('zone')) {
            $query->byZone($request->zone);
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

        // Limit per_page to prevent excessive data
        $perPage = min($request->get('per_page', 10), 50);
        
        $monuments = $query->select([
            'id', 'title', 'description', 'content', 'image', 'location', 'zone', 
            'latitude', 'longitude', 'is_world_wonder', 'status', 'created_at', 
            'updated_at', 'created_by'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        // Get locale from request or default to 'vi'
        $locale = $request->get('locale', 'vi');
        
        // Transform monuments to include localized content
        $transformedMonuments = $monuments->getCollection()->map(function ($monument) use ($locale) {
            // Get translation for the requested locale
            $translation = $monument->translation($locale);
            
            // Truncate content for list view
            $content = $translation ? $translation->content : $monument->content;
            $truncatedContent = $content ? Str::limit(strip_tags($content), 200) : null;
            
            $data = [
                'id' => $monument->id,
                'title' => $translation ? $translation->title : $monument->title,
                'description' => $translation ? $translation->description : $monument->description,
                'excerpt' => $truncatedContent, // Use excerpt instead of full content
                'image' => $monument->image ? (filter_var($monument->image, FILTER_VALIDATE_URL) ? $monument->image : asset('storage/' . $monument->image)) : null,
                'location' => $translation ? $translation->location : $monument->location,
                'zone' => $monument->zone,
                'latitude' => $monument->latitude,
                'longitude' => $monument->longitude,
                'is_world_wonder' => $monument->is_world_wonder,
                'status' => $monument->status,
                'created_at' => $monument->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $monument->updated_at->format('Y-m-d H:i:s'),
                'creator' => $monument->creator ? [
                    'id' => $monument->creator->id,
                    'name' => $monument->creator->name
                ] : null,
                'has_translation' => $translation ? true : false,
            ];
            
            return $data;
        });

        // Replace the collection with transformed data
        $monuments->setCollection($transformedMonuments);

        // Cache the response for 5 minutes
        return response()->json($monuments)->header('Cache-Control', 'public, max-age=300');
    }

    public function show(Request $request, Monument $monument)
    {
        // Check if monument is approved for public access
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'moderator'])) {
            if ($monument->status !== 'approved') {
                return response()->json(['message' => 'Monument not found'], 404);
            }
        }

        $monument->load([
            'creator',
            'gallery',
            'translations',
            'feedbacks' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }
        ]);

        // Get locale from request or default to 'vi'
        $locale = $request->get('locale', 'vi');
        
        // Transform monument to include localized content
        $data = $monument->toArray();
        
        // Get translation for the requested locale
        $translation = $monument->translation($locale);
        
        if ($translation) {
            // Use translated content
            $data['title'] = $translation->title;
            $data['description'] = $translation->description;
            $data['history'] = $translation->history;
            $data['content'] = $translation->content;
            $data['location'] = $translation->location;
            $data['has_translation'] = true;
        } else {
            // If no translation, keep original content (Vietnamese) and mark as no translation
            $data['has_translation'] = false;
        }

        // Add total feedback count
        $data['total_feedbacks'] = $monument->feedbacks()->count();

        return response()->json($data);
    }

    /**
     * Get more feedbacks for a monument (pagination)
     */
    public function getFeedbacks(Request $request, Monument $monument)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 5);
        
        $feedbacks = $monument->feedbacks()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($feedbacks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'history' => 'required|string',
            'zone' => 'required|in:East,North,West,South',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,pending,approved',
        ]);

        $data = $request->only(['title', 'description', 'history', 'zone', 'status']);
        $data['created_by'] = auth()->id();

        // Apply approval requirements based on settings
        $user = auth()->user();
        if ($data['status'] === 'pending') {
            // Check if approval is required based on settings
            if (\App\Services\SettingsService::isMonumentApprovalRequired()) {
                // Approval required - only admin content is auto-approved
                if ($user->role === 'admin') {
                    $data['status'] = 'approved';
                } else {
                    $data['status'] = 'pending';
                }
            } else {
                // No approval required - auto-approve all content
                $data['status'] = 'approved';
            }
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('monuments', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $monument = Monument::create($data);
        $monument->load('creator');

        return response()->json($monument, 201);
    }

    public function update(Request $request, Monument $monument)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'history' => 'required|string',
            'zone' => 'required|in:East,North,West,South',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,pending,approved',
        ]);

        $data = $request->only(['title', 'description', 'history', 'zone', 'status']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($monument->image) {
                Storage::disk('public')->delete($monument->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('monuments', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $monument->update($data);
        $monument->load('creator');

        return response()->json($monument);
    }

    public function destroy(Monument $monument)
    {
        $user = auth()->user();

        // Check permissions: Admin can delete anything, Moderator can only delete non-approved content
        if ($user->role === 'moderator' && $monument->status === 'approved') {
            return response()->json(['error' => 'Cannot delete approved content. Only administrators can delete approved content.'], 403);
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

        return response()->json(['message' => 'Monument deleted successfully']);
    }

    public function approve(Monument $monument)
    {
        $user = auth()->user();

        // Only admin can approve content
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Only administrators can approve content.'], 403);
        }

        $monument->update(['status' => 'approved']);
        $monument->load('creator');

        return response()->json($monument);
    }

    public function submitFeedback(Request $request, Monument $monument)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'monument_id' => $monument->id,
        ]);

        return response()->json($feedback, 201);
    }

    public function zones()
    {
        return response()->json([
            'zones' => ['East', 'North', 'West', 'South', 'Central']
        ]);
    }
}
