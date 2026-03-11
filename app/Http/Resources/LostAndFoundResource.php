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
            'finder'      => $this->whenLoaded('finder', fn() => [
                'username'   => $this->finder->user->username,
                'first_name' => $this->finder->first_name,
                'last_name'  => $this->finder->last_name,
                'phone'      => $this->finder->user->phone,
                'image'      => $this->finder->getFirstMediaUrl('image'),
            ]),
            'created_at'  => $this->created_at,
        ];
    }
}