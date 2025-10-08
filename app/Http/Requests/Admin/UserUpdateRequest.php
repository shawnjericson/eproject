<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('admin.name'),
            'email' => __('admin.email'),
            'password' => __('admin.password'),
            'role' => __('admin.role'),
            'status' => __('admin.status'),
        ];
    }
}







