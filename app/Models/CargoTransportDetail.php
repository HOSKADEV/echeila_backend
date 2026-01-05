<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTransportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_point_id',
        'delivery_point_id',
        'delivery_time',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

    public function pickupPoint()
    {
        return $this->belongsTo(Location::class, 'pickup_point_id');
    }

    public function deliveryPoint()
    {
        return $this->belongsTo(Location::class, 'delivery_point_id');
    }
}
