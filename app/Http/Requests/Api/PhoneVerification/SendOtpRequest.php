<?php

namespace App\Http\Requests\Api\PhoneVerification;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
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
        ];
    }
}
