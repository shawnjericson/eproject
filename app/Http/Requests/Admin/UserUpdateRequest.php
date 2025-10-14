<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $currentUser = auth()->user();
        $userBeingEdited = $this->route('user');
        
        // Nếu đang cố đổi role
        if ($this->has('role') && $this->role !== $userBeingEdited->role) {
            // Moderator không được đổi role
            if ($currentUser->isModerator()) {
                abort(403, 'Moderator không có quyền thay đổi role của người dùng.');
            }
            // Admin không được đổi role của chính mình
            if ($userBeingEdited->id === $currentUser->id) {
                abort(403, 'Bạn không thể thay đổi role của chính mình.');
            }
        }

        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? null;
        $currentUser = auth()->user();
        $userBeingEdited = $this->route('user');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Chỉ yêu cầu role nếu không phải moderator và không phải đang edit chính mình
        if (!$currentUser->isModerator() && $userBeingEdited->id !== $currentUser->id) {
            $rules['role'] = 'required|in:admin,moderator';
        }

        // Chỉ yêu cầu status nếu không phải đang edit chính mình
        if ($userBeingEdited->id !== $currentUser->id) {
            $rules['status'] = 'required|in:active,inactive';
        }

        return $rules;
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