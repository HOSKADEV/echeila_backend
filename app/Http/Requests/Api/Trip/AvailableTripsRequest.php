<?php

namespace App\Http\Requests\Api\Trip;

use App\Constants\TripType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvailableTripsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_type' => ['required', 'string', Rule::in([TripType::MRT_TRIP, TripType::ESP_TRIP])],
            'starting_time' => 'required|date_format:Y-m-d H:i|after:now',
            'number_of_seats' => 'sometimes|nullable|integer|min:1|max:50',
        ];
    }

    public function validateResolved()
    {
    }
}