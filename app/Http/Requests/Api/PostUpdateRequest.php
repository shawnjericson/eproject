<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $language = $this->input('language');

        $rules = [
            'language' => 'required|in:en,vi',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,pending,approved,rejected',
        ];

        if ($language === 'vi') {
            $rules['title_vi'] = 'required|string|max:255';
            $rules['description_vi'] = 'nullable|string';
            $rules['content_vi'] = 'required|string';
        } else {
            $rules['title'] = 'required|string|max:255';
            $rules['description'] = 'nullable|string';
            $rules['content'] = 'required|string';
        }

        return $rules;
    }
}



