<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Track visitor and return current count
     */
    public function track(Request $request)
    {
        // Get visitor's IP address
        $ipAddress = $request->ip();

        // Get user agent
        $userAgent = $request->userAgent();

        // Check if this IP has visited in the last 24 hours
        $hasVisitedRecently = VisitorLog::hasVisitedRecently($ipAddress, 24);

        // If not visited recently, log the visit and increment counter
        if (!$hasVisitedRecently) {
            // Log the visitor
            VisitorLog::logVisitor($ipAddress, $userAgent);

            // Get current count from site settings
            $currentCount = (int) SiteSetting::get('visitor_count', 0);

            // Increment and save
            $newCount = $currentCount + 1;
            SiteSetting::set('visitor_count', $newCount);
        }

        // Return current visitor count
        $visitorCount = (int) SiteSetting::get('visitor_count', 0);

        return response()->json([
            'visitor_count' => $visitorCount,
            'is_new_visitor' => !$hasVisitedRecently,
        ]);
    }

    /**
     * Get visitor statistics
     */
    public function stats()
    {
        $stats = [
            'total_visitors' => (int) SiteSetting::get('visitor_count', 0),
            'unique_ips' => VisitorLog::getUniqueVisitorCount(),
            'total_visits' => VisitorLog::getTotalVisitCount(),
        ];

        return response()->json($stats);
    }

    /**
     * Get current visitor count (public endpoint)
     */
    public function count()
    {
        $visitorCount = (int) SiteSetting::get('visitor_count', 0);

        return response()->json([
            'visitor_count' => $visitorCount,
        ]);
    }
}
