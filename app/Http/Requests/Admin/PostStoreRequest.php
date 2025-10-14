<?php

namespace App\Http\Requests\Admin;

use App\Services\SettingsService;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxImageSize = SettingsService::getMaxImageSize() * 1024; // Convert MB to KB
        $allowedTypes = implode(',', SettingsService::getAllowedImageTypes());
        $maxContentLength = SettingsService::getMaxPostContentLength();
        
        return [
            'language' => 'required|in:en,vi',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => "required|string|max:{$maxContentLength}",
            'image' => "nullable|image|mimes:{$allowedTypes}|max:{$maxImageSize}",
            'status' => 'required|in:draft,pending,approved,rejected',
        ];
    }

    public function attributes(): array
    {
        return [
            'language' => __('admin.language'),
            'title' => __('admin.title'),
            'description' => __('admin.description'),
            'content' => __('admin.content'),
            'image' => __('admin.image'),
            'status' => __('admin.status'),
        ];
    }

    public function messages(): array
    {
        $maxImageSize = SettingsService::getMaxImageSize();
        $maxContentLength = SettingsService::getMaxPostContentLength();
        
        return [
            'language.required' => __('validation.custom.language.required'),
            'title.required' => __('validation.custom.title.required'),
            'content.required' => __('validation.custom.content.required'),
            'content.max' => "Content cannot exceed {$maxContentLength} characters.",
            'image.max' => "Image size cannot exceed {$maxImageSize}MB.",
            'image.mimes' => 'Image must be one of: ' . implode(', ', SettingsService::getAllowedImageTypes()),
            'status.required' => __('validation.custom.status.required'),
        ];
    }
}



