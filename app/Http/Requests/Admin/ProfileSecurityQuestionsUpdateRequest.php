<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSecurityQuestionsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'security_question_1' => 'required|string|max:255',
            'security_answer_1' => 'required|string|max:255',
            'security_question_2' => 'nullable|string|max:255',
            'security_answer_2' => 'nullable|string|max:255',
            'security_question_3' => 'nullable|string|max:255',
            'security_answer_3' => 'nullable|string|max:255',
        ];
    }
}







