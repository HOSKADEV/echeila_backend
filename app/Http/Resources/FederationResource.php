<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FederationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'creation_date' => $this->creation_date,
            'image' => $this->getFirstMediaUrl('image'),
            'drivers_count' => $this->whenCounted('drivers'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}