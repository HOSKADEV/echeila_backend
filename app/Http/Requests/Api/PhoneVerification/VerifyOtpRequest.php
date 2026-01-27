<?php

namespace App\Http\Requests\Api\PhoneVerification;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'code' => 'required|string',
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
            'code.required' => __('validation.custom.code.required'),
            'code.string' => __('validation.custom.code.string'),
        ];
    }
}
