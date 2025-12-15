<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'model_id' => $this->model_id,
            'color_id' => $this->color_id,
            'production_year' => $this->production_year,
            'plate_number' => $this->plate_number,
            'image' => $this->getFirstMediaUrl('image'),
            'permit' => $this->getFirstMediaUrl('permit'),
            'model' => new VehicleModelResource($this->whenLoaded('model')),
            'color' => new ColorResource($this->whenLoaded('color')),
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }
}