<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TestUploadController extends Controller
{
    public function store(Request $request)
    {
        // Set PHP limits programmatically
        ini_set('upload_max_filesize', '5M');
        ini_set('post_max_size', '20M');
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '256M');

        Log::info('=== TEST UPLOAD START ===');
        Log::info('PHP Limits:', [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ]);
        Log::info('Request data:', $request->except(['featured_image']));
        Log::info('Has file:', [$request->hasFile('featured_image')]);
        
        try {
            // Minimal data
            $data = [
                'title' => $request->title ?: 'Test Title',
                'location' => $request->location ?: 'Test Location',
                'zone' => $request->zone ?: 'North',
                'content' => $request->content ?: 'Test Content',
                'description' => $request->content ?: 'Test Content',
                'history' => $request->content ?: 'Test Content',
                'status' => $request->status ?: 'draft',
                'created_by' => 1, // Hard-coded for test
            ];

            // Try to save image FIRST
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                Log::info('File info:', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'error' => $file->getError(),
                    'valid' => $file->isValid(),
                    'mime' => $file->getMimeType()
                ]);

                if ($file->isValid()) {
                    $filename = time() . '_test.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('monuments', $filename, 'public');
                    $data['image'] = $path; // Add to data BEFORE creating monument
                    Log::info('Image saved and added to data:', ['path' => $path]);
                } else {
                    Log::error('File is not valid:', ['error' => $file->getError()]);
                }
            }

            Log::info('Final data to save:', $data);

            // Create monument
            $monument = Monument::create($data);
            Log::info('Monument created:', ['id' => $monument->id, 'image' => $monument->image]);
            
            return response()->json(['success' => true, 'monument_id' => $monument->id]);
            
        } catch (\Exception $e) {
            Log::error('ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
