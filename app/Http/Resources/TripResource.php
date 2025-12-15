<?php

namespace App\Http\Resources;

use App\Constants\TripType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'type' => $this->type,
            //'type_name' => TripType::get_name($this->type),
            'status' => $this->status,
            'note' => $this->note,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            
            // Include available seats if calculated (for available trips API)
            'available_seats' => $this->when(isset($this->available_seats), $this->available_seats),
            
            // Include details using polymorphic relationship with dedicated resources
            'details' => $this->when($this->detailable, function () {
                return $this->formatTripDetails();
            }),

            // Include driver information
            'driver' => new DriverResource($this->whenLoaded('driver')),

            // Include client when relation is loaded
            'client' => $this->when(
                $this->relationLoaded('client'),
                new TripClientResource($this->client)
            ),
            
            // Include cargo when relation is loaded
            'cargo' => $this->when(
                $this->relationLoaded('cargo'),
                new TripCargoResource($this->cargo)
            ),
        ];
    }

    /**
     * Format trip details using dedicated resources based on trip type
     */
    protected function formatTripDetails()
    {
        if (!$this->detailable) {
            return null;
        }

        return match ($this->type) {
            TripType::TAXI_RIDE => new TaxiRideDetailResource($this->detailable),
            TripType::CAR_RESCUE => new CarRescueDetailResource($this->detailable),
            TripType::CARGO_TRANSPORT => new CargoTransportDetailResource($this->detailable),
            TripType::WATER_TRANSPORT => new WaterTransportDetailResource($this->detailable),
            TripType::PAID_DRIVING => new PaidDrivingDetailResource($this->detailable),
            TripType::MRT_TRIP, TripType::ESP_TRIP => new InternationalTripDetailResource($this->detailable),
            default => $this->detailable->toArray(),
        };
    }
}