<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SettingsService;

class HealthController extends Controller
{
    /**
     * Health check endpoint for maintenance mode detection
     */
    public function check(Request $request)
    {
        // Check if maintenance mode is enabled
        if (SettingsService::isMaintenanceModeEnabled()) {
            return response()->json([
                'error' => 'maintenance_mode',
                'message' => 'Website is currently under maintenance',
                'maintenance_mode' => true,
                'timestamp' => now()->toISOString()
            ], 503);
        }
        
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'message' => 'Service is running normally'
        ], 200);
    }

    /**
     * Alternative health check endpoint
     */
    public function status(Request $request)
    {
        return response()->json([
            'status' => 'ok',
            'maintenance_mode' => SettingsService::isMaintenanceModeEnabled(),
            'timestamp' => now()->toISOString()
        ], 200);
    }
}