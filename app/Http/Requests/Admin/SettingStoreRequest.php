<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255|unique:site_settings',
            'value' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'key' => __('admin.key'),
            'value' => __('admin.value'),
        ];
    }
}







