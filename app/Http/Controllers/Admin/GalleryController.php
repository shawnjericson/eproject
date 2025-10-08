<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryStoreRequest;
use App\Http\Requests\Admin\GalleryUpdateRequest;
use App\Models\Gallery;
use App\Models\Monument;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    public function index(Request $request)
    {
        $query = Gallery::with('monument');

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

        $galleries = $query->orderBy('created_at', 'desc')->paginate(12);
        $monuments = Monument::approved()->get();

        return view('admin.gallery.index', compact('galleries', 'monuments'));
    }

    public function create()
    {
        $monuments = Monument::approved()->get();
        return view('admin.gallery.create', compact('monuments'));
    }

    public function store(GalleryStoreRequest $request)
    {
        $data = $request->only(['monument_id', 'title', 'description']);

        if ($request->hasFile('image')) {
            Log::info('Creating gallery image with Cloudinary:', [
                'monument_id' => $request->monument_id,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_size' => $request->file('image')->getSize()
            ]);

            try {
                // Upload to Cloudinary
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'), 'gallery');

                if ($uploadResult['success']) {
                    $data['image_path'] = $uploadResult['url']; // Store full Cloudinary URL
                    Log::info('Gallery image uploaded to Cloudinary:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Gallery Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return redirect()->back()->with('error', 'Image upload failed: ' . $uploadResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Gallery image upload exception:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Image upload failed.');
            }
        }

        $gallery = Gallery::create($data);

        return redirect()->route('admin.gallery.show', $gallery)->with('success', 'Gallery image added successfully!');
    }

    public function show(Gallery $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $monuments = Monument::approved()->get();
        return view('admin.gallery.edit', compact('gallery', 'monuments'));
    }

    public function update(GalleryUpdateRequest $request, Gallery $gallery)
    {
        $data = $request->only(['monument_id', 'title', 'description']);

        if ($request->hasFile('image')) {
            Log::info('Updating gallery image with Cloudinary:', [
                'gallery_id' => $gallery->id,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_size' => $request->file('image')->getSize()
            ]);

            try {
                // Upload new image to Cloudinary
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'), 'gallery');

                if ($uploadResult['success']) {
                    $data['image_path'] = $uploadResult['url']; // Store full Cloudinary URL
                    Log::info('Gallery image uploaded to Cloudinary:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Gallery Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return redirect()->back()->with('error', 'Image upload failed: ' . $uploadResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Gallery image upload exception:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Image upload failed.');
            }
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.show', $gallery)->with('success', 'Gallery image updated successfully!');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image deleted successfully!');
    }
}
