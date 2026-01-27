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

    public function messages()
    {
        return [
            'trip_type.required' => __('validation.custom.trip_type.required'),
            'trip_type.string' => __('validation.custom.trip_type.string'),
            'trip_type.in' => __('validation.custom.trip_type.in'),
            'starting_time.required' => __('validation.custom.starting_time.required'),
            'starting_time.date_format' => __('validation.custom.starting_time.date_format'),
            'starting_time.after' => __('validation.custom.starting_time.after'),
            'number_of_seats.integer' => __('validation.custom.number_of_seats.integer'),
            'number_of_seats.min' => __('validation.custom.number_of_seats.min'),
            'number_of_seats.max' => __('validation.custom.number_of_seats.max'),
        ];
    }
}
