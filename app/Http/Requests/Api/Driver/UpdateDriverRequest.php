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
}