<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Monument;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
            'total_monuments' => Monument::count(),
            'approved_monuments' => Monument::approved()->count(),
            'pending_monuments' => Monument::where('status', 'pending')->count(),
            'total_feedbacks' => Feedback::count(),
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'moderator_users' => User::where('role', 'moderator')->count(),
        ];

        // Recent activities
        $recent_posts = Post::with('creator')
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();

        $recent_monuments = Monument::with('creator')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        $recent_feedbacks = Feedback::with('monument')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        // Posts by status
        $posts_by_status = [
            'draft' => Post::where('status', 'draft')->count(),
            'pending' => Post::where('status', 'pending')->count(),
            'approved' => Post::where('status', 'approved')->count(),
            'rejected' => Post::where('status', 'rejected')->count(),
        ];

        // Monuments by zone
        $monuments_by_zone = [
            'East' => Monument::approved()->byZone('East')->count(),
            'North' => Monument::approved()->byZone('North')->count(),
            'West' => Monument::approved()->byZone('West')->count(),
            'South' => Monument::approved()->byZone('South')->count(),
        ];

        return response()->json([
            'stats' => $stats,
            'recent_posts' => $recent_posts,
            'recent_monuments' => $recent_monuments,
            'recent_feedbacks' => $recent_feedbacks,
            'posts_by_status' => $posts_by_status,
            'monuments_by_zone' => $monuments_by_zone,
        ]);
    }
}
