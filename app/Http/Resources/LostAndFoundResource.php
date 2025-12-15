<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LostAndFoundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'image' => $this->getFirstMediaUrl('image'),
            'created_at' => $this->created_at,
            'passenger' => new PassengerResource($this->passenger)
        ];
    }
}