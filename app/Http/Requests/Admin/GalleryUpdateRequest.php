<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GalleryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'monument_id' => 'required|exists:monuments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'monument_id' => __('admin.monument'),
            'title' => __('admin.title'),
            'image' => __('admin.image'),
        ];
    }
}







