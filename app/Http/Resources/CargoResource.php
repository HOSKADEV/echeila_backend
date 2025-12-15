<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoResource extends JsonResource
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
            'description' => $this->description,
            'weight' => $this->weight,
            'image' => $this->getFirstMediaUrl('image'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Include passenger information
            'passenger' => new PassengerResource($this->whenLoaded('passenger')),
        ];
    }
}