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
        $recent_posts = Post::with('creator')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_monuments = Monument::with('creator')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_feedbacks = Feedback::with('monument')->orderBy('created_at', 'desc')->limit(5)->get();

        //Model::count(): đếm nhanh số bản ghi.
        //where(...)->count(): đếm theo điều kiện (ví dụ status).
        //with('creator'): eager load quan hệ để tránh N+1 query khi ra view.
        //orderBy('created_at', 'desc')->limit(5)->get(): lấy 5 bản ghi mới nhất.
        //view('...') + compact(...): trả dữ liệu cho Blade.

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_monuments', 'recent_feedbacks'));
    }
}
