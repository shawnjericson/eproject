<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Monument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('monument:id,title,zone');

        // Filter by category (monument zone)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('monument', function($q) use ($request) {
                $q->where('zone', $request->category);
            });
        }

        // Filter by monument - only apply if monument_id is provided
        if ($request->filled('monument_id')) {
            $query->where('monument_id', $request->monument_id);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Support larger page sizes for infinite scroll
        $perPage = min($request->get('per_page', 24), 100); // Max 100 per request

        $galleries = $query->orderBy('created_at', 'desc')
                          ->paginate($perPage);

        return response()->json($galleries);
    }

    /**
     * Get available categories from monuments
     */
    public function categories()
    {
        $categories = Monument::select('zone')
            ->distinct()
            ->whereNotNull('zone')
            ->pluck('zone')
            ->toArray();

        return response()->json([
            'categories' => array_merge(['all'], $categories)
        ]);
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('monument');
        return response()->json($gallery);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monument_id' => 'required|exists:monuments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $monument = Monument::findOrFail($request->monument_id);

        $image = $request->file('image');
        $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('gallery', $imageName, 'public');

        $gallery = Gallery::create([
            'monument_id' => $request->monument_id,
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        $gallery->load('monument');

        return response()->json($gallery, 201);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('gallery', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $gallery->update($data);
        $gallery->load('monument');

        return response()->json($gallery);
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return response()->json(['message' => 'Gallery item deleted successfully']);
    }
}
