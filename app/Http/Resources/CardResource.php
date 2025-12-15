<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'number' => $this->number,
            'expiration_date' => $this->expiration_date?->format('Y-m-d'),
            'front_image' => $this->getFirstMediaUrl('front_image'),
            'back_image' => $this->getFirstMediaUrl('back_image'),
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }
}