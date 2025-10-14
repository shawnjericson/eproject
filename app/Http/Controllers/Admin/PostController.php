<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostStoreRequest;
use App\Http\Requests\Admin\PostUpdateRequest;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    private $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    public function index(Request $request)
    {
        // Add caching for better performance
        $cacheKey = 'admin_posts_' . $request->get('page', 1) . '_' . $request->get('status', 'all') . '_' . $request->get('author', 'all') . '_' . $request->get('search', '');
        
        $query = Post::with(['creator:id,name', 'translations']);

        // Filter by user role - moderators only see their own posts
        if (auth()->user()->isModerator()) {
            $query->where('created_by', auth()->id());
        }

        // Filter by status - only apply if status is provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by author - only apply if author is provided
        if ($request->filled('author')) {
            $query->where('created_by', $request->author);
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

        // Select only necessary columns for list view
        $posts = $query->select([
            'id', 'title', 'description', 'image', 'status', 'created_at', 
            'updated_at', 'created_by', 'published_at'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Get current locale for displaying content
        $currentLocale = app()->getLocale();

        return response()
            ->view('admin.posts.index', compact('posts', 'currentLocale'))
            ->header('Cache-Control', 'private, max-age=60'); // Cache for 1 minute
    }

    public function create()
    {
        return view('admin.posts.create_multilingual');
    }

    public function store(PostStoreRequest $request)
    {
        // Create the main post
        $postData = [
            'title' => $request->title, // Fallback title
            'description' => $request->description, // Fallback description
            'content' => $request->content, // Fallback content
            'status' => $request->status,
            'created_by' => auth()->id(),
        ];

        // Apply approval requirements based on settings
        $user = auth()->user();
        if ($request->status === 'pending') {
            // Check if approval is required based on settings
            if (\App\Services\SettingsService::isPostApprovalRequired()) {
                // Approval required - only admin content is auto-approved
                if ($user->role === 'admin') {
                    $postData['status'] = 'approved';
                } else {
                    $postData['status'] = 'pending';
                }
            } else {
                // No approval required - auto-approve all content
                $postData['status'] = 'approved';
            }
        }

        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'), 'posts');

                if ($uploadResult['success']) {
                    $postData['image'] = $uploadResult['url'];
                    Log::info('Post image uploaded to Cloudinary:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Post Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return redirect()->back()->with('error', 'Image upload failed: ' . $uploadResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Post image upload exception:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Image upload failed.');
            }
        }

        if ($postData['status'] === 'approved') {
            $postData['published_at'] = now();
        }

        $post = Post::create($postData);

        // Create translation
        PostTranslation::create([
            'post_id' => $post->id,
            'language' => $request->language,
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
        ]);

        // Send notification to admin when moderator creates content that needs approval
        if ($user->role === 'moderator' && $post->status === 'pending') {
            $admin = \App\Models\User::where('role', 'admin')->first();
            if ($admin) {
                try {
                    \App\Services\NotificationService::postCreatedByModerator(
                        $admin,
                        $post,
                        $user
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send moderator creation notification: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['creator', 'translations']);
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $post->load('translations');
        return view('admin.posts.edit_multilingual', compact('post'));
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        // Update main post
        $postData = ['status' => $request->status];

        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'), 'posts');

                if ($uploadResult['success']) {
                    $postData['image'] = $uploadResult['url'];
                    Log::info('Post image updated via Cloudinary:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Post Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return redirect()->back()->with('error', 'Image upload failed: ' . $uploadResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Post image upload exception:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Image upload failed.');
            }
        }

        if ($postData['status'] === 'approved' && $post->status !== 'approved') {
            $postData['published_at'] = now();
        }

        $post->update($postData);

        // Update translations
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

        // Update fallback title and content from first translation
        $firstTranslation = $request->translations[0];
        $post->update([
            'title' => $firstTranslation['title'],
            'content' => $firstTranslation['content'],
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Request $request, Post $post)
    {
        $user = auth()->user();

        // Check permissions: Admin can delete anything, Moderator can only delete non-approved content
        if ($user->role === 'moderator' && $post->status === 'approved') {
            abort(403, 'Cannot delete approved content. Only administrators can delete approved content.');
        }

        // Validate deletion reason
        $request->validate([
            'deletion_reason' => 'required|string|max:500'
        ]);

        // Send notification to post creator
        if ($post->creator && $post->creator->email) {
            try {
                \App\Services\NotificationService::postDeleted(
                    $post->creator, 
                    $post, 
                    $request->deletion_reason, 
                    $user
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send post deletion notification: ' . $e->getMessage());
            }
        }

        // Soft delete the post (don't delete images, just mark as deleted)
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post moved to trash successfully!');
    }

    /**
     * Upload image for CKEditor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        try {
            $uploadResult = $this->cloudinaryService->uploadImage($request->file('upload'), 'posts/content');

            if ($uploadResult['success']) {
                return response()->json([
                    'url' => $uploadResult['url']
                ]);
            } else {
                return response()->json([
                    'error' => ['message' => 'Upload failed: ' . $uploadResult['error']]
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => ['message' => 'Upload failed: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function approve(Post $post)
    {
        $user = auth()->user();

        // Only Admin can approve posts
        if ($user->role !== 'admin') {
            abort(403, 'Only administrators can approve posts.');
        }

        $post->update([
            'status' => 'approved',
            'published_at' => now()
        ]);

        return redirect()->back()->with('success', 'Post approved successfully!');
    }

    public function reject(Request $request, Post $post)
    {
        $user = auth()->user();

        // Only Admin can reject posts
        if ($user->role !== 'admin') {
            abort(403, 'Only administrators can reject posts.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $post->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        // Send notification to post creator
        if ($post->creator && $post->creator->email) {
            try {
                \App\Services\NotificationService::postRejected(
                    $post->creator, 
                    $post, 
                    $request->rejection_reason, 
                    $user
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send post rejection notification: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Post rejected successfully!');
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->back()->with('success', 'Post restored successfully!');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        
        // Only admins can permanently delete
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can permanently delete posts.');
        }

        // Now we can delete images since it's permanent
        if ($post->image && !filter_var($post->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->forceDelete();

        return redirect()->back()->with('success', 'Post permanently deleted!');
    }
}
