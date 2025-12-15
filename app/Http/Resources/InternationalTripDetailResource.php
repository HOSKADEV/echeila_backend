<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternationalTripDetailResource extends JsonResource
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
            'direction' => $this->direction,
            'starting_place' => $this->starting_place,
            'starting_time' => $this->starting_time,
            'arrival_time' => $this->arrival_time,
            'duration_minutes' => $this->starting_time && $this->arrival_time
                ? $this->starting_time->diffInMinutes($this->arrival_time)
                : null,
            'total_seats' => $this->total_seats,
            'seat_price' => $this->seat_price,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
