<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApprovalMiddleware
{
    /**
     * Handle an incoming request.
     * Only admin users can approve content.
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

        if ($user->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only administrators can approve content.'], 403);
            }
            abort(403, 'Only administrators can approve content.');
        }

        return $next($request);
    }
}
