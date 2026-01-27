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
            'metadata' => 'nullable|array',
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
            'total_fees' => 'required|numeric|min:0',
        ];
    }

    protected function getCargoTransportRules(): array
    {
        return [
            // Trip details
            'driver_id' => 'required|exists:drivers,id',
            'pickup_point.longitude' => 'required|numeric|between:-180,180',
            'pickup_point.latitude' => 'required|numeric|between:-90,90',
            'pickup_point.name' => 'required|string|max:255',
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
            'total_fees' => 'required|numeric|min:0',
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
            'total_fees' => 'required|numeric|min:0',
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

    public function messages()
    {
        return [
            // Common fields
            'driver_id.required' => __('validation.custom.driver_id.required'),
            'driver_id.exists' => __('validation.custom.driver_id.exists'),
            'note.string' => __('validation.custom.note.string'),
            'note.max' => __('validation.custom.note.max'),
            'metadata.array' => __('validation.custom.metadata.array'),
            'total_fees.required' => __('validation.custom.total_fees.required'),
            'total_fees.numeric' => __('validation.custom.total_fees.numeric'),
            'total_fees.min' => __('validation.custom.total_fees.min'),

            // Taxi Ride fields
            'ride_type.required' => __('validation.custom.ride_type.required'),
            'ride_type.string' => __('validation.custom.ride_type.string'),
            'ride_type.in' => __('validation.custom.ride_type.in'),
            'starting_point_id.required' => __('validation.custom.starting_point_id.required'),
            'starting_point_id.exists' => __('validation.custom.starting_point_id.exists'),
            'arrival_point_id.required' => __('validation.custom.arrival_point_id.required'),
            'arrival_point_id.exists' => __('validation.custom.arrival_point_id.exists'),
            'number_of_seats.required' => __('validation.custom.number_of_seats.required'),
            'number_of_seats.integer' => __('validation.custom.number_of_seats.integer'),
            'number_of_seats.min' => __('validation.custom.number_of_seats.min'),
            'number_of_seats.max' => __('validation.custom.number_of_seats.max'),

            // Location coordinate fields
            'starting_point.longitude.required' => __('validation.custom.starting_point.longitude.required'),
            'starting_point.longitude.numeric' => __('validation.custom.starting_point.longitude.numeric'),
            'starting_point.longitude.between' => __('validation.custom.starting_point.longitude.between'),
            'starting_point.latitude.required' => __('validation.custom.starting_point.latitude.required'),
            'starting_point.latitude.numeric' => __('validation.custom.starting_point.latitude.numeric'),
            'starting_point.latitude.between' => __('validation.custom.starting_point.latitude.between'),
            'starting_point.name.required' => __('validation.custom.starting_point.name.required'),
            'starting_point.name.string' => __('validation.custom.starting_point.name.string'),
            'starting_point.name.max' => __('validation.custom.starting_point.name.max'),

            'arrival_point.longitude.required' => __('validation.custom.arrival_point.longitude.required'),
            'arrival_point.longitude.numeric' => __('validation.custom.arrival_point.longitude.numeric'),
            'arrival_point.longitude.between' => __('validation.custom.arrival_point.longitude.between'),
            'arrival_point.latitude.required' => __('validation.custom.arrival_point.latitude.required'),
            'arrival_point.latitude.numeric' => __('validation.custom.arrival_point.latitude.numeric'),
            'arrival_point.latitude.between' => __('validation.custom.arrival_point.latitude.between'),
            'arrival_point.name.required' => __('validation.custom.arrival_point.name.required'),
            'arrival_point.name.string' => __('validation.custom.arrival_point.name.string'),
            'arrival_point.name.max' => __('validation.custom.arrival_point.name.max'),

            // Car Rescue fields
            'breakdown_point.longitude.required' => __('validation.custom.breakdown_point.longitude.required'),
            'breakdown_point.longitude.numeric' => __('validation.custom.breakdown_point.longitude.numeric'),
            'breakdown_point.longitude.between' => __('validation.custom.breakdown_point.longitude.between'),
            'breakdown_point.latitude.required' => __('validation.custom.breakdown_point.latitude.required'),
            'breakdown_point.latitude.numeric' => __('validation.custom.breakdown_point.latitude.numeric'),
            'breakdown_point.latitude.between' => __('validation.custom.breakdown_point.latitude.between'),
            'breakdown_point.name.required' => __('validation.custom.breakdown_point.name.required'),
            'breakdown_point.name.string' => __('validation.custom.breakdown_point.name.string'),
            'breakdown_point.name.max' => __('validation.custom.breakdown_point.name.max'),
            'malfunction_type.required' => __('validation.custom.malfunction_type.required'),
            'malfunction_type.string' => __('validation.custom.malfunction_type.string'),
            'malfunction_type.in' => __('validation.custom.malfunction_type.in'),

            // Cargo Transport fields
            'pickup_point.longitude.required' => __('validation.custom.pickup_point.longitude.required'),
            'pickup_point.longitude.numeric' => __('validation.custom.pickup_point.longitude.numeric'),
            'pickup_point.longitude.between' => __('validation.custom.pickup_point.longitude.between'),
            'pickup_point.latitude.required' => __('validation.custom.pickup_point.latitude.required'),
            'pickup_point.latitude.numeric' => __('validation.custom.pickup_point.latitude.numeric'),
            'pickup_point.latitude.between' => __('validation.custom.pickup_point.latitude.between'),
            'pickup_point.name.required' => __('validation.custom.pickup_point.name.required'),
            'pickup_point.name.string' => __('validation.custom.pickup_point.name.string'),
            'pickup_point.name.max' => __('validation.custom.pickup_point.name.max'),

            'delivery_point.longitude.required' => __('validation.custom.delivery_point.longitude.required'),
            'delivery_point.longitude.numeric' => __('validation.custom.delivery_point.longitude.numeric'),
            'delivery_point.longitude.between' => __('validation.custom.delivery_point.longitude.between'),
            'delivery_point.latitude.required' => __('validation.custom.delivery_point.latitude.required'),
            'delivery_point.latitude.numeric' => __('validation.custom.delivery_point.latitude.numeric'),
            'delivery_point.latitude.between' => __('validation.custom.delivery_point.latitude.between'),
            'delivery_point.name.required' => __('validation.custom.delivery_point.name.required'),
            'delivery_point.name.string' => __('validation.custom.delivery_point.name.string'),
            'delivery_point.name.max' => __('validation.custom.delivery_point.name.max'),

            'delivery_time.required' => __('validation.custom.delivery_time.required'),
            'delivery_time.date' => __('validation.custom.delivery_time.date'),
            'delivery_time.after' => __('validation.custom.delivery_time.after'),

            'cargo.description.required' => __('validation.custom.cargo.description.required'),
            'cargo.description.string' => __('validation.custom.cargo.description.string'),
            'cargo.description.max' => __('validation.custom.cargo.description.max'),
            'cargo.weight.required' => __('validation.custom.cargo.weight.required'),
            'cargo.weight.numeric' => __('validation.custom.cargo.weight.numeric'),
            'cargo.weight.min' => __('validation.custom.cargo.weight.min'),
            'cargo.images.array' => __('validation.custom.cargo.images.array'),
            'cargo.images.*.image' => __('validation.custom.cargo.images.image'),
            'cargo.images.*.mimes' => __('validation.custom.cargo.images.mimes'),
            'cargo.images.*.max' => __('validation.custom.cargo.images.max'),

            // Water Transport fields
            'water_type.required' => __('validation.custom.water_type.required'),
            'water_type.string' => __('validation.custom.water_type.string'),
            'water_type.in' => __('validation.custom.water_type.in'),
            'quantity.required' => __('validation.custom.quantity.required'),
            'quantity.numeric' => __('validation.custom.quantity.numeric'),
            'quantity.min' => __('validation.custom.quantity.min'),

            // Paid Driving fields
            'starting_time.required' => __('validation.custom.starting_time.required'),
            'starting_time.date' => __('validation.custom.starting_time.date'),
            'starting_time.after' => __('validation.custom.starting_time.after'),
            'vehicle_type.required' => __('validation.custom.vehicle_type.required'),
            'vehicle_type.string' => __('validation.custom.vehicle_type.string'),
            'vehicle_type.in' => __('validation.custom.vehicle_type.in'),

            // International Trip fields
            'direction.required' => __('validation.custom.direction.required'),
            'direction.string' => __('validation.custom.direction.string'),
            'direction.in' => __('validation.custom.direction.in'),
            'starting_place.required' => __('validation.custom.starting_place.required'),
            'starting_place.string' => __('validation.custom.starting_place.string'),
            'starting_place.max' => __('validation.custom.starting_place.max'),
            'arrival_time.required' => __('validation.custom.arrival_time.required'),
            'arrival_time.date' => __('validation.custom.arrival_time.date'),
            'arrival_time.after' => __('validation.custom.arrival_time.after'),
            'total_seats.required' => __('validation.custom.total_seats.required'),
            'total_seats.integer' => __('validation.custom.total_seats.integer'),
            'total_seats.min' => __('validation.custom.total_seats.min'),
            'total_seats.max' => __('validation.custom.total_seats.max'),
            'seat_price.required' => __('validation.custom.seat_price.required'),
            'seat_price.numeric' => __('validation.custom.seat_price.numeric'),
            'seat_price.min' => __('validation.custom.seat_price.min'),
        ];
    }

}
