<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOwnershipMiddleware
{
    /**
     * Handle an incoming request.
     * Moderators can only edit/delete their own content.
     * Admins can edit/delete any content.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin can do anything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // For moderators, check ownership
        if ($user->role === 'moderator') {
            $content = $this->getContentFromRoute($request);
            
            if ($content) {
                // Check if moderator is the creator
                if ($content->created_by !== $user->id) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'You can only edit or delete content that you created.'
                        ], 403);
                    }
                    abort(403, 'You can only edit or delete content that you created.');
                }
            }
        }

        return $next($request);
    }

    /**
     * Get the content model from the route parameters
     */
    private function getContentFromRoute(Request $request)
    {
        // Check for post
        if ($request->route('post')) {
            return $request->route('post');
        }

        // Check for monument
        if ($request->route('monument')) {
            return $request->route('monument');
        }

        // Check for gallery
        // Gallery doesn't have created_by field, so we need to check through monument
        if ($request->route('gallery')) {
            $gallery = $request->route('gallery');
            // For gallery, check if the monument belongs to the user
            if ($gallery->monument) {
                return $gallery->monument;
            }
        }

        // Check for feedback
        // Feedback doesn't have created_by field, so anyone can delete it
        if ($request->route('feedback')) {
            return null; // Allow moderators to manage feedbacks
        }

        return null;
    }
}

