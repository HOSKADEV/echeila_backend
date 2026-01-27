<?php

namespace App\Http\Requests\Api\PhoneVerification;

use Illuminate\Foundation\Http\FormRequest;

class CheckPhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validateResolved()
    {
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => __('validation.custom.phone.required'),
            'phone.string' => __('validation.custom.phone.string'),
        ];
    }
}
