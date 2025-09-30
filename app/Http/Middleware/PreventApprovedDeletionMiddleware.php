<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Monument;
use App\Models\Gallery;

class PreventApprovedDeletionMiddleware
{
    /**
     * Handle an incoming request.
     * Prevent moderators from deleting approved content.
     * Only admins can delete approved content.
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

        // Admin can delete anything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // For moderators, check if the content is approved
        if ($user->role === 'moderator') {
            $content = $this->getContentFromRoute($request);
            
            if ($content && $content->status === 'approved') {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Cannot delete approved content. Only administrators can delete approved content.'], 403);
                }
                abort(403, 'Cannot delete approved content. Only administrators can delete approved content.');
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

        // For gallery, we don't have status field, so gallery can always be deleted by moderators
        // Gallery items are not "approved" in the same way as posts/monuments
        if ($request->route('gallery')) {
            return null; // Allow deletion
        }

        return null;
    }
}
