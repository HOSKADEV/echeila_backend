<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/^\+\d{1,3}\d{9}$/|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'device_token' => 'nullable|string',
        ];
    }

      public function validateResolved()
  {
  }

    public function messages(){
        return [
            'phone.required' => __('validation.custom.phone.required'),
            'phone.string' => __('validation.custom.phone.string'),
            'phone.regex' => __('validation.custom.phone.regex'),
            'phone.unique' => __('validation.custom.phone.unique'),
            'password.required' => __('validation.custom.password.required'),
            'password.string' => __('validation.custom.password.string'),
            'password.min' => __('validation.custom.password.min'),
            'password.confirmed' => __('validation.custom.password.confirmed'),
            'first_name.string' => __('validation.custom.first_name.string'),
            'first_name.max' => __('validation.custom.first_name.max'),
            'last_name.string' => __('validation.custom.last_name.string'),
            'last_name.max' => __('validation.custom.last_name.max'),
            'device_token.string' => __('validation.custom.device_token.string'),
        ];
    }

}
