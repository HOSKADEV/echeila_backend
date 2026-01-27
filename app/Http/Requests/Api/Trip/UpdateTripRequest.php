<?php

namespace App\Http\Requests\Api\Trip;

use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Constants\RideType;
use App\Constants\WaterType;
use App\Constants\Direction;
use App\Constants\VehicleType;
use App\Constants\MalfunctionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in(TripStatus::all())],
            'note' => 'nullable|string|max:1000',
            'metadata' => 'nullable|array',
            'direction' => ['nullable', 'string', Rule::in(Direction::all())],
            'starting_place' => 'nullable|string|max:255',
            'starting_time' => 'nullable|date|after:now',
            'arrival_time' => 'nullable|date|after:starting_time',
            'total_seats' => 'nullable|integer|min:1|max:50',
            'seat_price' => 'nullable|numeric|min:0',
        ];

    }

        public function validateResolved()
    {
    }

    protected function getInternationalTripUpdateRules(): array
    {
        return [

        ];
    }

    public function messages()
    {
        return [
            'status.string' => __('validation.custom.status.string'),
            'status.in' => __('validation.custom.status.in'),
            'note.string' => __('validation.custom.note.string'),
            'note.max' => __('validation.custom.note.max'),
            'metadata.array' => __('validation.custom.metadata.array'),
            'direction.string' => __('validation.custom.direction.string'),
            'direction.in' => __('validation.custom.direction.in'),
            'starting_place.string' => __('validation.custom.starting_place.string'),
            'starting_place.max' => __('validation.custom.starting_place.max'),
            'starting_time.date' => __('validation.custom.starting_time.date'),
            'starting_time.after' => __('validation.custom.starting_time.after'),
            'arrival_time.date' => __('validation.custom.arrival_time.date'),
            'arrival_time.after' => __('validation.custom.arrival_time.after'),
            'total_seats.integer' => __('validation.custom.total_seats.integer'),
            'total_seats.min' => __('validation.custom.total_seats.min'),
            'total_seats.max' => __('validation.custom.total_seats.max'),
            'seat_price.numeric' => __('validation.custom.seat_price.numeric'),
            'seat_price.min' => __('validation.custom.seat_price.min'),
        ];
    }
}
