<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|exists:users,phone',
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'phone.required' => __('validation.custom.phone.required'),
            'phone.string' => __('validation.custom.phone.string'),
            'phone.exists' => __('validation.custom.phone.exists'),
            'new_password.required' => __('validation.custom.new_password.required'),
            'new_password.string' => __('validation.custom.new_password.string'),
            'new_password.min' => __('validation.custom.new_password.min'),
            'new_password.confirmed' => __('validation.custom.new_password.confirmed'),
        ];
    }
}
