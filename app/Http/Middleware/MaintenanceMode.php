<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SettingsService;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (SettingsService::isMaintenanceModeEnabled()) {
            // Always allow admin routes during maintenance
            if ($request->is('admin*')) {
                return $next($request);
            }
            
            // Allow admin users to access the site during maintenance
            if ($request->user() && $request->user()->isAdmin()) {
                return $next($request);
            }
            
            // Allow access to admin login page during maintenance
            if ($request->is('admin/login') || $request->is('login')) {
                return $next($request);
            }
            
            // Handle API requests - return JSON response for frontend to detect
            if ($request->is('api*') || $request->expectsJson()) {
                return response()->json([
                    'error' => 'maintenance_mode',
                    'message' => 'Website is currently under maintenance. Please try again later.',
                    'maintenance_page_url' => url('/maintenance')
                ], 503);
            }
            
            // Show maintenance page for all other requests (frontend webapp)
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}