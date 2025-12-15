<?php

namespace App\Http\Requests\Api\Trip;

use App\Constants\TripType;
use App\Constants\RideType;
use App\Constants\WaterType;
use App\Constants\Direction;
use App\Constants\VehicleType;
use App\Constants\MalfunctionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tripType = $this->route('type');

        $baseRules = [
            'note' => 'nullable|string|max:1000',
        ];

        return array_merge($baseRules, $this->getTripTypeSpecificRules($tripType));
    }

    public function validateResolved()
    {
    }

    protected function getTripTypeSpecificRules(string $tripType): array
    {
        return match ($tripType) {
            TripType::TAXI_RIDE => $this->getTaxiRideRules(),
            TripType::CAR_RESCUE => $this->getCarRescueRules(),
            TripType::CARGO_TRANSPORT => $this->getCargoTransportRules(),
            TripType::WATER_TRANSPORT => $this->getWaterTransportRules(),
            TripType::PAID_DRIVING => $this->getPaidDrivingRules(),
            TripType::MRT_TRIP, TripType::ESP_TRIP => $this->getInternationalTripRules(),
            default => [],
        };
    }

    protected function getTaxiRideRules(): array
    {
        $rideType = $this->input('ride_type');

        $baseRules = [
            'driver_id' => 'required|exists:drivers,id',
            'ride_type' => ['required', 'string', Rule::in(RideType::all())],
            'total_fees' => 'required|numeric|min:0',
        ];

        if ($rideType === RideType::PRIVATE) {
            return array_merge($baseRules, [
                'note' => 'nullable|string|max:1000',
                'starting_point.longitude' => 'required|numeric|between:-180,180',
                'starting_point.latitude' => 'required|numeric|between:-90,90',
                'starting_point.name' => 'required|string|max:255',
                'arrival_point.longitude' => 'required|numeric|between:-180,180',
                'arrival_point.latitude' => 'required|numeric|between:-90,90',
                'arrival_point.name' => 'required|string|max:255',
            ]);
        }

        if ($rideType === RideType::SHARED) {
            return array_merge($baseRules, [
                'starting_point_id' => 'required|exists:wilayas,id',
                'arrival_point_id' => 'required|exists:wilayas,id',
                'number_of_seats' => 'required|integer|min:1|max:8',
            ]);
        }

        // Fallback for unknown ride types
        return $baseRules;
    }

    protected function getCarRescueRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'required|exists:drivers,id',
            'breakdown_point.longitude' => 'required|numeric|between:-180,180',
            'breakdown_point.latitude' => 'required|numeric|between:-90,90',
            'breakdown_point.name' => 'required|string|max:255',
            'delivery_time' => 'required|date|after:now',
            'malfunction_type' => ['required', 'string', Rule::in(MalfunctionType::all())],
        ];
    }

    protected function getCargoTransportRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'required|exists:drivers,id',
            'delivery_point.longitude' => 'required|numeric|between:-180,180',
            'delivery_point.latitude' => 'required|numeric|between:-90,90',
            'delivery_point.name' => 'required|string|max:255',
            'delivery_time' => 'required|date|after:now',
            'total_fees' => 'required|numeric|min:0',

            // Cargo details
            'cargo.description' => 'required|string|max:1000',
            'cargo.weight' => 'required|numeric|min:0.1',
            'cargo.images' => 'nullable|array',
            'cargo.images.*' => 'image|mimes:jpeg,png,jpg,gif|max:8192',
        ];
    }

    protected function getWaterTransportRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'required|exists:drivers,id',
            'delivery_point.longitude' => 'required|numeric|between:-180,180',
            'delivery_point.latitude' => 'required|numeric|between:-90,90',
            'delivery_point.name' => 'required|string|max:255',
            'delivery_time' => 'required|date|after:now',
            'water_type' => ['required', 'string', Rule::in(WaterType::all())],
            'quantity' => 'required|numeric|min:0.1',
        ];
    }

    protected function getPaidDrivingRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'required|exists:drivers,id',
            'starting_time' => 'required|date|after:now',
            'vehicle_type' => ['required', 'string', Rule::in(VehicleType::all())],

            // Location coordinates
            'starting_point.longitude' => 'required|numeric|between:-180,180',
            'starting_point.latitude' => 'required|numeric|between:-90,90',
            'starting_point.name' => 'required|string|max:255',
            'arrival_point.longitude' => 'required|numeric|between:-180,180',
            'arrival_point.latitude' => 'required|numeric|between:-90,90',
            'arrival_point.name' => 'required|string|max:255',
        ];
    }

    protected function getInternationalTripRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'nullable|exists:drivers,id',
            'direction' => ['required', 'string', Rule::in(Direction::all())],
            'starting_place' => 'required|string|max:255',
            'starting_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:starting_time',
            'total_seats' => 'required|integer|min:1|max:50',
            'seat_price' => 'required|numeric|min:0',
        ];
    }

}