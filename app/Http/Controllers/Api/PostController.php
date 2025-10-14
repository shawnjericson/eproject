<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostStoreRequest;
use App\Http\Requests\Api\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Add caching for better performance
        $cacheKey = 'posts_' . $request->get('page', 1) . '_' . $request->get('per_page', 12) . '_' . $request->get('locale', 'vi');
        
        $query = Post::with(['creator:id,name', 'translations']);

        // For public API, only show published posts
        if (!$request->user() || !in_array($request->user()->role, ['admin', 'moderator'])) {
            $query->published();
        } else {
            // For admin/moderator, allow filtering by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        // Search - works independently of filters
        // Search in both main table and translations table
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm)
                  ->orWhereHas('translations', function($tq) use ($searchTerm) {
                      $tq->where('title', 'like', $searchTerm)
                         ->orWhere('description', 'like', $searchTerm)
                         ->orWhere('content', 'like', $searchTerm);
                  });
            });
        }

        // Limit per_page to prevent excessive data
        $perPage = min($request->get('per_page', 10), 50);
        
        $posts = $query->select([
            'id', 'title', 'content', 'image', 'status', 'published_at', 
            'created_at', 'updated_at', 'created_by'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        // Get locale from request or default to 'vi'
        $locale = $request->get('locale', 'vi');
        
        // Transform posts to include localized content
        $transformedPosts = $posts->getCollection()->map(function ($post) use ($locale) {
            // Get translation for the requested locale
            $translation = $post->translation($locale);
            
            // Truncate content for list view
            $content = $translation ? $translation->content : $post->content;
            $truncatedContent = $content ? Str::limit(strip_tags($content), 200) : null;
            
            $data = [
                'id' => $post->id,
                'title' => $translation ? $translation->title : $post->title,
                'description' => $translation ? $translation->description : $post->description, // Fallback to posts.description
                'excerpt' => $truncatedContent, // Use excerpt instead of full content
                'image' => $post->image ? (filter_var($post->image, FILTER_VALIDATE_URL) ? $post->image : asset('storage/' . $post->image)) : null,
                'status' => $post->status,
                'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
                'created_at' => $post->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
                'creator' => $post->creator ? [
                    'id' => $post->creator->id,
                    'name' => $post->creator->name
                ] : null,
                'has_translation' => $translation ? true : false,
            ];
            
            return $data;
        });

        // Replace the collection with transformed data
        $posts->setCollection($transformedPosts);

        // Cache the response for 5 minutes
        return response()->json($posts)->header('Cache-Control', 'public, max-age=300');
    }

    public function show(Request $request, Post $post)
    {
        // Check if post is published for public access
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'moderator'])) {
            if ($post->status !== 'approved' || !$post->published_at || $post->published_at > now()) {
                return response()->json(['message' => 'Post not found'], 404);
            }
        }

        $post->load(['creator:id,name', 'translations']);

        // Get locale from request or default to 'vi'
        $locale = $request->get('locale', 'vi');
        
        // Get translation for the requested locale
        $translation = $post->translation($locale);
        
        $data = [
            'id' => $post->id,
            'title' => $translation ? $translation->title : $post->title,
            'description' => $translation ? $translation->description : $post->description, // Fallback to posts.description
            'content' => $translation ? $translation->content : $post->content,
            'image' => $post->image ? (filter_var($post->image, FILTER_VALIDATE_URL) ? $post->image : asset('storage/' . $post->image)) : null,
            'status' => $post->status,
            'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
            'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            'creator' => $post->creator ? [
                'id' => $post->creator->id,
                'name' => $post->creator->name
            ] : null,
            'has_translation' => $translation ? true : false,
        ];

        return response()->json($data);
    }

    public function store(PostStoreRequest $request)
    {
        $data = [
            'language' => $request->language,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ];

        // Apply approval requirements based on settings
        $user = auth()->user();
        if ($data['status'] === 'pending') {
            // Check if approval is required based on settings
            if (\App\Services\SettingsService::isPostApprovalRequired()) {
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

        // Fill fields based on language
        if ($request->language === 'vi') {
            $data['title_vi'] = $request->title_vi;
            $data['description_vi'] = $request->description_vi;
            $data['content_vi'] = $request->content_vi;
        } else {
            $data['title'] = $request->title;
            $data['description'] = $request->description;
            $data['content'] = $request->content;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $titleForSlug = $request->language === 'vi' ? $request->title_vi : $request->title;
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('posts', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        if ($data['status'] === 'approved') {
            $data['published_at'] = now();
        }

        $post = Post::create($data);
        $post->load('creator');

        return response()->json($post, 201);
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $data = [
            'language' => $request->language,
            'status' => $request->status,
        ];

        // Fill fields based on language
        if ($request->language === 'vi') {
            $data['title_vi'] = $request->title_vi;
            $data['description_vi'] = $request->description_vi;
            $data['content_vi'] = $request->content_vi;
        } else {
            $data['title'] = $request->title;
            $data['description'] = $request->description;
            $data['content'] = $request->content;
        }

        if ($request->hasFile('image')) {
            // Only delete local images, not Cloudinary URLs
            if ($post->image && !filter_var($post->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($post->image);
            }

            $image = $request->file('image');
            $titleForSlug = $request->language === 'vi' ? $request->title_vi : $request->title;
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('posts', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        if ($data['status'] === 'approved' && $post->status !== 'approved') {
            $data['published_at'] = now();
        }

        $post->update($data);
        $post->load('creator');

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();

        // Check permissions: Admin can delete anything, Moderator can only delete non-approved content
        if ($user->role === 'moderator' && $post->status === 'approved') {
            return response()->json(['error' => 'Cannot delete approved content. Only administrators can delete approved content.'], 403);
        }

        // Only delete local images, not Cloudinary URLs
        if ($post->image && !filter_var($post->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function approve(Post $post)
    {
        $user = auth()->user();

        // Only admin can approve content
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Only administrators can approve content.'], 403);
        }

        $post->update([
            'status' => 'approved',
            'published_at' => now()
        ]);

        $post->load('creator');

        return response()->json($post);
    }
}
