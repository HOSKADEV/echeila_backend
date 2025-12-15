<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxiRideDetailResource extends JsonResource
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
            //'starting_point_id' => $this->starting_point_id,
            //'starting_point_type' => $this->starting_point_type,
            //'arrival_point_id' => $this->arrival_point_id,
            //'arrival_point_type' => $this->arrival_point_type,
            'ride_type' => $this->ride_type,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            
            // Include polymorphic relationships
            'starting_point' => new LocationResource($this->whenLoaded('startingPoint')),
            'arrival_point' => new LocationResource($this->whenLoaded('arrivalPoint')),
        ];
    }
}