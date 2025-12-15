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
}