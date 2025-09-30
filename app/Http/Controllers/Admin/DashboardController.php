<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Monument;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
            'approved_posts' => Post::where('status', 'approved')->count(),
            'total_monuments' => Monument::count(),
            'pending_monuments' => Monument::where('status', 'pending')->count(),
            'approved_monuments' => Monument::where('status', 'approved')->count(),
            'total_feedbacks' => Feedback::count(),
            'total_users' => User::count(),
        ];

        // Get recent data safely
        $recentPosts = Post::orderBy('created_at', 'desc')->limit(5)->get();
        $recentMonuments = Monument::orderBy('created_at', 'desc')->limit(5)->get();
        $recentFeedbacks = Feedback::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard_modern', compact('stats', 'recentPosts', 'recentMonuments', 'recentFeedbacks'));
    }
}
