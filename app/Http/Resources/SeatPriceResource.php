<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatPriceResource extends JsonResource
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
            'starting_wilaya_id' => $this->starting_wilaya_id,
            'arrival_wilaya_id' => $this->arrival_wilaya_id,
            'default_seat_price' => $this->default_seat_price,
            'starting_wilaya' => new WilayaResource($this->whenLoaded('startingWilaya')),
            'arrival_wilaya' => new WilayaResource($this->whenLoaded('arrivalWilaya')),
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }
}