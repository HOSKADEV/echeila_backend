<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoTransportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function deliveryPoint()
    {
        return $this->belongsTo(Location::class, 'delivery_point_id');
    }
}