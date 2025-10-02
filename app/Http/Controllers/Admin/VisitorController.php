<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorController extends Controller
{
    /**
     * Display visitor statistics page
     */
    public function index()
    {
        return view('admin.visitors.index');
    }

    /**
     * Get recent visitors (API endpoint for admin)
     */
    public function recent()
    {
        $visitors = VisitorLog::orderBy('visited_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($visitors);
    }

    /**
     * Delete a visitor log
     */
    public function destroy($id)
    {
        $visitor = VisitorLog::findOrFail($id);
        $visitor->delete();

        return response()->json([
            'message' => 'Visitor log deleted successfully'
        ]);
    }

    /**
     * Clear old visitor logs (older than 90 days)
     */
    public function clearOld()
    {
        $deleted = VisitorLog::where('visited_at', '<', Carbon::now()->subDays(90))
            ->delete();

        return response()->json([
            'message' => 'Old logs cleared successfully',
            'deleted' => $deleted
        ]);
    }

    /**
     * Export visitor logs to CSV
     */
    public function export()
    {
        $visitors = VisitorLog::orderBy('visited_at', 'desc')->get();

        $filename = 'visitor_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($visitors) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['ID', 'IP Address', 'User Agent', 'Visited At', 'Created At']);

            // Data rows
            foreach ($visitors as $visitor) {
                fputcsv($file, [
                    $visitor->id,
                    $visitor->ip_address,
                    $visitor->user_agent,
                    $visitor->visited_at,
                    $visitor->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
