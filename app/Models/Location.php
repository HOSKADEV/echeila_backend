<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Polymorphic relationships
    public function taxiRideStartingPoints()
    {
        return $this->morphMany(TaxiRideDetail::class, 'starting_point');
    }

    public function taxiRideArrivalPoints()
    {
        return $this->morphMany(TaxiRideDetail::class, 'arrival_point');
    }

    public function getUrlAttribute(){
        return "https://maps.google.com/?q={$this->latitude},{$this->longitude}";
    }
}