<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:draft,pending,approved,rejected',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'translations' => 'required|array',
            'translations.*.language' => 'required|in:en,vi',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.content' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'translations.*.title' => __('admin.title'),
            'translations.*.description' => __('admin.description'),
            'translations.*.content' => __('admin.content'),
        ];
    }

    public function messages(): array
    {
        return [
            'translations.required' => __('validation.custom.translations.required'),
        ];
    }
}



