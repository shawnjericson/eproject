<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('monument');

        // No status filter - show all feedbacks (auto-display after submission)

        // Filter by monument - only apply if monument_id is provided
        if ($request->filled('monument_id')) {
            $query->where('monument_id', $request->monument_id);
        }

        // Search - works independently of filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $feedbacks = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('per_page', 20));

        return response()->json($feedbacks);
    }

    public function show(Feedback $feedback)
    {
        $feedback->load('monument');
        return response()->json($feedback);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:1000',
            'comment' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'monument_id' => 'nullable|exists:monuments,id',
            'type' => 'nullable|string|in:general,monument_review',
        ]);

        // Use 'comment' if provided, otherwise use 'message'
        $feedbackData = $request->all();
        if ($request->has('comment') && !$request->has('message')) {
            $feedbackData['message'] = $request->comment;
        }

        // Auto-approve all reviews
        $feedbackData['status'] = 'approved';

        $feedback = Feedback::create($feedbackData);
        $feedback->load('monument');

        return response()->json($feedback, 201);
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    }
}
