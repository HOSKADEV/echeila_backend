<?php

namespace App\Models;

use App\Constants\WaterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterTransportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_point_id',
        'delivery_time',
        'water_type',
        'quantity',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
        'quantity' => 'decimal:2',
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