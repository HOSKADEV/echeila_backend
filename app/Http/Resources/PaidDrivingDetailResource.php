<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaidDrivingDetailResource extends JsonResource
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
            //'arrival_point_id' => $this->arrival_point_id,
            'starting_time' => $this->starting_time,
            'vehicle_type' => $this->vehicle_type,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            
            // Include location relationships
            'starting_point' => new LocationResource($this->whenLoaded('startingPoint')),
            'arrival_point' => new LocationResource($this->whenLoaded('arrivalPoint')),
        ];
    }
}