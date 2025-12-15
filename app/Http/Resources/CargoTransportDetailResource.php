<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoTransportDetailResource extends JsonResource
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
            //'delivery_point_id' => $this->delivery_point_id,
            'delivery_time' => $this->delivery_time,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            
            // Include location relationship
            'delivery_point' => new LocationResource($this->whenLoaded('deliveryPoint')),
        ];
    }
}