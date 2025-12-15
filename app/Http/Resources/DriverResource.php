<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'federation_id' => $this->federation_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'email' => $this->email,
            'phone' => $this->user->phone,
            'status' => $this->status,
            'image' => $this->getFirstMediaUrl('image'),
            'federation' => new FederationResource($this->whenLoaded('federation')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'cards' => CardResource::collection($this->whenLoaded('cards')),
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),

            'trip_count' => $this->trip_count,
            'review_average' => $this->review_average,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }
}
