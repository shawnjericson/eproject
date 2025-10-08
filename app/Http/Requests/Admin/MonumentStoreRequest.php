<?php

namespace App\Http\Requests\Admin;

use App\Services\SettingsService;
use Illuminate\Foundation\Http\FormRequest;

class MonumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxImageSize = SettingsService::getMaxImageSize() * 1024; // Convert MB to KB
        $allowedTypes = implode(',', SettingsService::getAllowedImageTypes());
        $maxDescriptionLength = SettingsService::getMaxMonumentDescriptionLength();
        $maxFilesPerMonument = SettingsService::getMaxFilesPerMonument();
        
        return [
            'language' => 'required|in:en,vi',
            'title' => 'required|string|max:255',
            'description' => "nullable|string|max:{$maxDescriptionLength}",
            'history' => 'nullable|string',
            'content' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'map_embed' => 'nullable|string',
            'zone' => 'required|in:East,North,West,South,Central',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_world_wonder' => 'nullable|boolean',
            'featured_image' => "nullable|image|mimes:{$allowedTypes}|max:{$maxImageSize}",
            'gallery_images.*' => "nullable|image|mimes:{$allowedTypes}|max:{$maxImageSize}",
            'gallery_images' => "max:{$maxFilesPerMonument}",
            'status' => 'required|in:draft,pending,approved',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('admin.title'),
            'zone' => __('admin.zone'),
            'status' => __('admin.status'),
            'featured_image' => __('admin.image'),
        ];
    }

    public function messages(): array
    {
        $maxImageSize = SettingsService::getMaxImageSize();
        $maxDescriptionLength = SettingsService::getMaxMonumentDescriptionLength();
        $maxFilesPerMonument = SettingsService::getMaxFilesPerMonument();
        
        return [
            'description.max' => "Description cannot exceed {$maxDescriptionLength} characters.",
            'featured_image.max' => "Image size cannot exceed {$maxImageSize}MB.",
            'featured_image.mimes' => 'Image must be one of: ' . implode(', ', SettingsService::getAllowedImageTypes()),
            'gallery_images.*.max' => "Each image size cannot exceed {$maxImageSize}MB.",
            'gallery_images.*.mimes' => 'Each image must be one of: ' . implode(', ', SettingsService::getAllowedImageTypes()),
            'gallery_images.max' => "Maximum {$maxFilesPerMonument} images allowed per monument.",
        ];
    }
}



