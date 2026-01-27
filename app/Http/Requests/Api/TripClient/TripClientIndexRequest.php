<?php

namespace App\Http\Requests\Api\TripClient;

use Illuminate\Foundation\Http\FormRequest;

class TripClientIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id'
        ];
    }

    public function validateResolved()
    {
    }

    public function messages()
    {
        return [
            'trip_id.required' => __('validation.custom.trip_id.required'),
            'trip_id.exists' => __('validation.custom.trip_id.exists'),
        ];
    }
}
