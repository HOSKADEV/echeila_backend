<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripCargoResource extends JsonResource
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
            'trip_id' => $this->trip_id,
            'cargo_id' => $this->cargo_id,
            'total_fees' => $this->total_fees,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'description' => $this->cargo->description,
            'weight' => $this->cargo->weight,
            'images' => $this->cargo->getMedia('images')->map(function ($item) {
                return $item->getUrl();
            }),
            'passenger' => $this->when($this->cargo->relationLoaded('passenger'), new PassengerResource($this->cargo->passenger)),
            
            // Include cargo information
            //'cargo' => new CargoResource($this->whenLoaded('cargo')),
        ];
    }
}