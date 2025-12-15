<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'trip_id' => $this->trip_id,
            'reviewer_id' => $this->reviewer_id,
            'reviewer_type' => $this->reviewer_type,
            'reviewer' => $this->whenLoaded('reviewer', function () {
                return [
                    'id' => $this->reviewer->id,
                    'fullname' => $this->reviewer->fullname,
                    'avatar_url' => $this->reviewer->avatar_url,
                    'type' => class_basename($this->reviewer_type),
                ];
            }),
            'reviewee_id' => $this->reviewee_id,
            'reviewee_type' => $this->reviewee_type,
            'reviewee' => $this->whenLoaded('reviewee', function () {
                return [
                    'id' => $this->reviewee->id,
                    'fullname' => $this->reviewee->fullname,
                    'avatar_url' => $this->reviewee->avatar_url,
                    'type' => class_basename($this->reviewee_type),
                ];
            }),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'trip' => $this->whenLoaded('trip', function () {
                return [
                    'id' => $this->trip->id,
                    'identifier' => $this->trip->identifier,
                    'type' => $this->trip->type,
                    'status' => $this->trip->status,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}