<?php

namespace App\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    use HasFactory, HasGoogleTranslationTrait;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'name' => 'json',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected array $translatable = [
        'name',
    ];

    public function startingSeatPrices()
    {
        return $this->hasMany(SeatPrice::class, 'starting_wilaya_id');
    }

    public function arrivalSeatPrices()
    {
        return $this->hasMany(SeatPrice::class, 'arrival_wilaya_id');
    }

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