<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Monument;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('monument');

        // Filter by monument - only apply if monument_id is provided
        if ($request->filled('monument_id')) {
            $query->where('monument_id', $request->monument_id);
        }

        // Filter by date range - only apply if days is provided
        if ($request->filled('days')) {
            $query->where('created_at', '>=', now()->subDays($request->days));
        }

        // Search - works independently of filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);
        $monuments = Monument::approved()->get();

        // Calculate stats
        $stats = [
            'total' => Feedback::count(),
            'today' => Feedback::whereDate('created_at', today())->count(),
            'this_week' => Feedback::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => Feedback::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.feedbacks.index', compact('feedbacks', 'monuments', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        // Mark as viewed when admin views the feedback
        if ($feedback->status === 'approved' && !$feedback->viewed_at) {
            $feedback->markAsViewed();
        }
        
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function markAllAsViewed()
    {
        $updated = Feedback::where('status', 'approved')->unviewed()->update(['viewed_at' => now()]);
        
        return response()->json([
            'success' => true, 
            'updated' => $updated,
            'message' => "Marked {$updated} feedbacks as viewed"
        ]);
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Feedback deleted successfully!');
    }
}
