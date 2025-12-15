<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'trip_id' => $this->trip_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            //'trip' => $this->whenLoaded('trip'),
            //'wallet' => $this->whenLoaded('wallet'),
        ];
    }
}