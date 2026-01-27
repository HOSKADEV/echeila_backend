<?php

namespace App\Http\Requests\Api\TripClient;

use App\Constants\TripType;
use App\Models\Trip;
use App\Models\InternationalTripDetail;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
            'number_of_seats' => 'required|integer|min:1|max:50',
            'note' => 'nullable|string|max:1000',
            'fullname' => 'nullable|required_with:phone|string|max:255',
            'phone' => 'nullable|required_with:fullname|string|regex:/^\+\d{1,3}\d{9}$/',
        ];
    }

    public function validateResolved()
    {
    }

    public function messages(): array
    {
        return [
            'trip_id.required' => __('validation.custom.trip_id.required'),
            'trip_id.exists' => __('validation.custom.trip_id.exists'),
            'number_of_seats.required' => __('validation.custom.number_of_seats.required'),
            'number_of_seats.integer' => __('validation.custom.number_of_seats.integer'),
            'number_of_seats.min' => __('validation.custom.number_of_seats.min'),
            'number_of_seats.max' => __('validation.custom.number_of_seats.max'),
            'note.string' => __('validation.custom.note.string'),
            'note.max' => __('validation.custom.note.max'),
            'fullname.required_with' => __('validation.custom.fullname.required_with'),
            'fullname.string' => __('validation.custom.fullname.string'),
            'fullname.max' => __('validation.custom.fullname.max'),
            'phone.required_with' => __('validation.custom.phone.required_with'),
            'phone.string' => __('validation.custom.phone.string'),
            'phone.regex' => __('validation.custom.phone.regex'),
        ];
    }
}
