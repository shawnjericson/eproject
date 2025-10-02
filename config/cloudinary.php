<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Cloudinary credentials.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Upload Presets
    |--------------------------------------------------------------------------
    |
    | Default upload settings for different types of media.
    |
    */
    
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', 'ml_default'),
    
    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    |
    | Default folders for organizing uploads.
    |
    */
    
    'folders' => [
        'avatars' => 'global-heritage/avatars',
        'posts' => 'global-heritage/posts',
        'monuments' => 'global-heritage/monuments',
        'gallery' => 'global-heritage/gallery',
    ],
];

