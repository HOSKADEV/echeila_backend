<?php

namespace App\Http\Requests\Api\Driver;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\DriverStatus;

class UpdateDriverRequest extends FormRequest
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
            'federation_id' => 'sometimes|exists:federations,id',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'birth_date' => 'sometimes|date|before:today',
            'email' => 'sometimes|email',
            'status' => 'sometimes|in:' . implode(',', DriverStatus::all()),
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:8192',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'federation_id.exists' => __('validation.custom.federation_id.exists'),
            'first_name.string' => __('validation.custom.first_name.string'),
            'first_name.max' => __('validation.custom.first_name.max'),
            'last_name.string' => __('validation.custom.last_name.string'),
            'last_name.max' => __('validation.custom.last_name.max'),
            'birth_date.date' => __('validation.custom.birth_date.date'),
            'birth_date.before' => __('validation.custom.birth_date.before'),
            'email.email' => __('validation.custom.email.email'),
            'status.in' => __('validation.custom.status.in'),
            'image.image' => __('validation.custom.image.image'),
            'image.mimes' => __('validation.custom.image.mimes'),
            'image.max' => __('validation.custom.image.max'),
        ];
    }
}
