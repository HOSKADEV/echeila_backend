<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRescueDetailResource extends JsonResource
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
            //'breakdown_point_id' => $this->breakdown_point_id,
            'delivery_time' => $this->delivery_time,
            'malfunction_type' => $this->malfunction_type,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            
            // Include location relationship
            'breakdown_point' => new LocationResource($this->whenLoaded('breakdownPoint')),
        ];
    }
}