<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LostAndFoundResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'image'       => $this->getFirstMediaUrl('image'),
            'status'      => $this->status,
            'finder_type' => $this->finder_type,
            'finder'      => $this->whenLoaded('finder', fn() => new UserResource($this->finder->user)),
            'created_at'  => $this->created_at,
        ];
    }
}