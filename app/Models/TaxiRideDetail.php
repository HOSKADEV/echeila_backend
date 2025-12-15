<?php

namespace App\Models;

use App\Constants\RideType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiRideDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_point_id',
        'starting_point_type',
        'arrival_point_id',
        'arrival_point_type',
        'ride_type',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

    // Polymorphic relationships - starting/arrival points can be location or wilaya
    public function startingPoint()
    {
        return $this->morphTo();
    }

    public function arrivalPoint()
    {
        return $this->morphTo();
    }
}