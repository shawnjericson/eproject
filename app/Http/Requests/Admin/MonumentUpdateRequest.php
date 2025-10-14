<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MonumentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'zone' => 'required|in:East,North,West,South,Central',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_world_wonder' => 'nullable|boolean',
            'status' => 'required|in:draft,pending,approved,rejected',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'map_embed' => 'nullable|string',
            'translations' => 'required|array',
            'translations.*.language' => 'required|in:en,vi',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.history' => 'nullable|string',
            'translations.*.content' => 'nullable|string',
            'translations.*.location' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'translations.*.title' => __('admin.title'),
            'status' => __('admin.status'),
            'zone' => __('admin.zone'),
        ];
    }
}



