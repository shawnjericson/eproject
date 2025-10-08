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
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Base queries - admins see all, moderators see only their own
        $postsQuery = $isAdmin ? Post::query() : Post::where('created_by', $user->id);
        $monumentsQuery = $isAdmin ? Monument::query() : Monument::where('created_by', $user->id);
        
        $stats = [
            'total_posts' => $postsQuery->count(),
            'pending_posts' => $postsQuery->where('status', 'pending')->count(),
            'approved_posts' => $postsQuery->where('status', 'approved')->count(),
            'total_monuments' => $monumentsQuery->count(),
            'pending_monuments' => $monumentsQuery->where('status', 'pending')->count(),
            'approved_monuments' => $monumentsQuery->where('status', 'approved')->count(),
            'total_feedbacks' => Feedback::count(), // Feedbacks are global
        ];
        
        // Add user stats only for admins
        if ($isAdmin) {
            $stats['total_users'] = User::count();
        }

        // Get recent data safely - filtered by role
        $recent_posts = $postsQuery->with('creator')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_monuments = $monumentsQuery->with('creator')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_feedbacks = Feedback::with('monument')->orderBy('created_at', 'desc')->limit(5)->get();

        //Model::count(): đếm nhanh số bản ghi.
        //where(...)->count(): đếm theo điều kiện (ví dụ status).
        //with('creator'): eager load quan hệ để tránh N+1 query khi ra view.
        //orderBy('created_at', 'desc')->limit(5)->get(): lấy 5 bản ghi mới nhất.
        //view('...') + compact(...): trả dữ liệu cho Blade.

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_monuments', 'recent_feedbacks', 'isAdmin'));
    }
}
