<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monument;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class CloudinaryTestController extends Controller
{
    private $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function store(Request $request)
    {
        Log::info('=== CLOUDINARY TEST START ===');
        Log::info('Request method:', [$request->method()]);
        Log::info('Request has file:', [$request->hasFile('featured_image')]);
        Log::info('All files:', [$request->allFiles()]);

        try {
            // Skip connection test - go straight to upload

            // Basic data
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

            // Upload to Cloudinary
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                Log::info('File info:', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'error' => $file->getError(),
                    'valid' => $file->isValid(),
                    'path' => $file->getRealPath()
                ]);

                $uploadResult = $this->cloudinaryService->uploadImage($file, 'monuments');

                if ($uploadResult['success']) {
                    $data['image'] = $uploadResult['url']; // Store full URL
                    Log::info('Cloudinary upload success:', ['url' => $uploadResult['url']]);
                } else {
                    Log::error('Cloudinary upload failed:', ['error' => $uploadResult['error']]);
                    return response()->json(['error' => 'Image upload failed: ' . $uploadResult['error']], 500);
                }
            } else {
                Log::info('No file uploaded - creating monument without image');
            }
            
            Log::info('Final data to save:', $data);
            
            // Create monument
            $monument = Monument::create($data);
            Log::info('Monument created:', ['id' => $monument->id, 'image' => $monument->image]);
            
            return response()->json([
                'success' => true, 
                'monument_id' => $monument->id,
                'image_url' => $monument->image
            ]);
            
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
