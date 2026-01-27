<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'old_password.required' => __('validation.custom.old_password.required'),
            'old_password.string' => __('validation.custom.old_password.string'),
            'new_password.required' => __('validation.custom.new_password.required'),
            'new_password.string' => __('validation.custom.new_password.string'),
            'new_password.min' => __('validation.custom.new_password.min'),
            'new_password.confirmed' => __('validation.custom.new_password.confirmed'),
        ];
    }
}
