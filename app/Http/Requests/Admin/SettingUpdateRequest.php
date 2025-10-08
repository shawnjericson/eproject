<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $settingId = $this->route('setting')?->id ?? null;

        return [
            'key' => 'required|string|max:255|unique:site_settings,key,' . $settingId,
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







