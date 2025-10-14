<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SettingsService;

class CheckRegistrationEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!SettingsService::isUserRegistrationEnabled()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'User registration is currently disabled.',
                    'error' => 'registration_disabled'
                ], 403);
            }
            
            // Return 403 error page
            abort(403, 'User registration is currently disabled.');
        }

        return $next($request);
    }
}
