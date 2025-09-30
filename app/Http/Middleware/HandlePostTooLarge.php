<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandlePostTooLarge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if POST data is too large
        $contentLength = $_SERVER['CONTENT_LENGTH'] ?? 0;
        $postCount = count($_POST);
        $filesCount = count($_FILES);

        // Log debug info
        Log::info('PostTooLarge Check:', [
            'content_length' => $contentLength,
            'post_count' => $postCount,
            'files_count' => $filesCount,
            'post_max_size' => ini_get('post_max_size'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        if (empty($_POST) && empty($_FILES) && $contentLength > 0) {
            $postMaxSize = $this->convertToBytes(ini_get('post_max_size'));

            Log::warning('PostTooLargeException detected:', [
                'content_length' => $contentLength,
                'post_max_size' => $postMaxSize,
                'formatted_content' => $this->formatBytes($contentLength),
                'formatted_max' => $this->formatBytes($postMaxSize)
            ]);

            return redirect()->back()->with('error',
                "⚠️ Form data too large!\n\n" .
                "Submitted: " . $this->formatBytes($contentLength) . "\n" .
                "Maximum allowed: " . $this->formatBytes($postMaxSize) . "\n\n" .
                "Please reduce:\n" .
                "• Image file sizes\n" .
                "• Content length\n" .
                "• Number of images"
            );
        }

        return $next($request);
    }

    /**
     * Convert PHP size format to bytes
     */
    private function convertToBytes($size)
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);
        $size = (int) $size;

        switch ($last) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }

        return $size;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
