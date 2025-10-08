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
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Base queries - admins see all, moderators see only their own
        $postsQuery = $isAdmin ? Post::query() : Post::where('created_by', $user->id);
        $monumentsQuery = $isAdmin ? Monument::query() : Monument::where('created_by', $user->id);
        
        $stats = [
            'total_posts' => $postsQuery->count(),
            'published_posts' => $postsQuery->published()->count(),
            'pending_posts' => $postsQuery->where('status', 'pending')->count(),
            'total_monuments' => $monumentsQuery->count(),
            'approved_monuments' => $monumentsQuery->approved()->count(),
            'pending_monuments' => $monumentsQuery->where('status', 'pending')->count(),
            'total_feedbacks' => Feedback::count(), // Feedbacks are global
        ];
        
        // Add user stats only for admins
        if ($isAdmin) {
            $stats['total_users'] = User::count();
            $stats['admin_users'] = User::where('role', 'admin')->count();
            $stats['moderator_users'] = User::where('role', 'moderator')->count();
        }

        // Recent activities - filtered by role
        $recent_posts = $postsQuery->with('creator')
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get();

        $recent_monuments = $monumentsQuery->with('creator')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        $recent_feedbacks = Feedback::with('monument')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        // Posts by status - filtered by role
        $posts_by_status = [
            'draft' => $postsQuery->where('status', 'draft')->count(),
            'pending' => $postsQuery->where('status', 'pending')->count(),
            'approved' => $postsQuery->where('status', 'approved')->count(),
            'rejected' => $postsQuery->where('status', 'rejected')->count(),
        ];

        // Monuments by zone - filtered by role
        $monuments_by_zone = [
            'East' => $monumentsQuery->approved()->byZone('East')->count(),
            'North' => $monumentsQuery->approved()->byZone('North')->count(),
            'West' => $monumentsQuery->approved()->byZone('West')->count(),
            'South' => $monumentsQuery->approved()->byZone('South')->count(),
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
