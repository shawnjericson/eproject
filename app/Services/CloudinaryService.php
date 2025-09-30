<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    public $cloudinary;

    public function __construct()
    {
        // Disable SSL verification for Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            putenv('CLOUDINARY_SECURE=false');
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    /**
     * Upload image to Cloudinary using simple HTTP POST
     */
    public function uploadImage(UploadedFile $file, $folder = 'monuments')
    {
        try {
            Log::info('Uploading to Cloudinary:', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'folder' => $folder
            ]);

            // Use simple HTTP upload to avoid SSL issues
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');

            $timestamp = time();
            $publicId = $folder . '/' . $timestamp . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            // Create signature - Cloudinary format
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp
            ];

            ksort($params);
            $signatureString = '';
            foreach ($params as $key => $value) {
                $signatureString .= $key . '=' . $value . '&';
            }
            $signatureString = rtrim($signatureString, '&'); // Remove trailing &
            $signatureString .= $apiSecret; // Append secret without &
            $signature = sha1($signatureString);

            // Prepare POST data
            $postData = [
                'file' => new \CURLFile($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName()),
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'public_id' => $publicId,
                'signature' => $signature
            ];

            // Upload using cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception("cURL Error: " . $error);
            }

            if ($httpCode !== 200) {
                throw new \Exception("HTTP Error: " . $httpCode . " - " . $response);
            }

            $result = json_decode($response, true);

            if (!$result || !isset($result['secure_url'])) {
                throw new \Exception("Invalid response: " . $response);
            }

            Log::info('Cloudinary upload success:', [
                'public_id' => $result['public_id'],
                'secure_url' => $result['secure_url']
            ]);

            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];

        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete image from Cloudinary
     */
    public function deleteImage($publicId)
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId);
            
            return [
                'success' => true,
                'result' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed:', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload multiple images to Cloudinary in parallel
     */
    public function uploadMultipleImages($files, $folder = 'monuments')
    {
        $results = [];
        $errors = [];

        Log::info('Starting batch upload:', ['count' => count($files), 'folder' => $folder]);

        foreach ($files as $index => $file) {
            if (!$file || !$file->isValid()) {
                $errors[$index] = 'Invalid file';
                continue;
            }

            try {
                $result = $this->uploadImage($file, $folder . '/gallery');

                if ($result['success']) {
                    $results[$index] = $result;
                    Log::info("Batch upload success [{$index}]:", ['url' => $result['url']]);
                } else {
                    $errors[$index] = $result['error'];
                    Log::error("Batch upload failed [{$index}]:", ['error' => $result['error']]);
                }
            } catch (\Exception $e) {
                $errors[$index] = $e->getMessage();
                Log::error("Batch upload exception [{$index}]:", ['error' => $e->getMessage()]);
            }
        }

        return [
            'success' => count($results) > 0,
            'results' => $results,
            'errors' => $errors,
            'uploaded_count' => count($results),
            'total_count' => count($files)
        ];
    }

    /**
     * Get optimized image URL
     */
    public function getOptimizedUrl($publicId, $width = null, $height = null)
    {
        try {
            $transformation = [];

            if ($width || $height) {
                $transformation['transformation'] = [
                    'width' => $width,
                    'height' => $height,
                    'crop' => 'fill',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ];
            }

            return $this->cloudinary->image($publicId, $transformation)->toUrl();
        } catch (\Exception $e) {
            Log::error('Cloudinary URL generation failed:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
