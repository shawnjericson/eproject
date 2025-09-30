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
        $query = Monument::with(['creator', 'translations']);

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

        $monuments = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('per_page', 10));

        return response()->json($monuments);
    }

    public function show(Monument $monument)
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
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return response()->json($monument);
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
            'zones' => ['East', 'North', 'West', 'South']
        ]);
    }
}
