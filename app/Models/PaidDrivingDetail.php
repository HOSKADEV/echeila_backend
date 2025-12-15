<?php

namespace App\Models;

use App\Constants\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidDrivingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_point_id',
        'arrival_point_id',
        'starting_time',
        'vehicle_type',
    ];

    protected $casts = [
        'starting_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

    public function startingPoint()
    {
        return $this->belongsTo(Location::class, 'starting_point_id');
    }

    public function arrivalPoint()
    {
        return $this->belongsTo(Location::class, 'arrival_point_id');
    }
}