<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Monument;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index()
    {
        // Only admins can access trash
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can access trash.');
        }

        $deletedPosts = Post::onlyTrashed()->with('creator')->orderBy('deleted_at', 'desc')->paginate(10, ['*'], 'posts_page');
        $deletedMonuments = Monument::onlyTrashed()->with('creator')->orderBy('deleted_at', 'desc')->paginate(10, ['*'], 'monuments_page');

        return view('admin.trash.index', compact('deletedPosts', 'deletedMonuments'));
    }

    public function restorePost($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->back()->with('success', 'Post restored successfully!');
    }

    public function restoreMonument($id)
    {
        $monument = Monument::onlyTrashed()->findOrFail($id);
        $monument->restore();

        return redirect()->back()->with('success', 'Monument restored successfully!');
    }

    public function forceDeletePost($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        
        // Only admins can permanently delete
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can permanently delete posts.');
        }

        $post->forceDelete();

        return redirect()->back()->with('success', 'Post permanently deleted!');
    }

    public function forceDeleteMonument($id)
    {
        $monument = Monument::onlyTrashed()->findOrFail($id);
        
        // Only admins can permanently delete
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can permanently delete monuments.');
        }

        $monument->forceDelete();

        return redirect()->back()->with('success', 'Monument permanently deleted!');
    }

    public function emptyTrash()
    {
        // Only admins can empty trash
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can empty trash.');
        }

        Post::onlyTrashed()->forceDelete();
        Monument::onlyTrashed()->forceDelete();

        return redirect()->back()->with('success', 'Trash emptied successfully!');
    }
}