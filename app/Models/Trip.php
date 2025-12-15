<?php

namespace App\Models;

use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Models\TaxiRideDetail;
use App\Models\CarRescueDetail;
use App\Models\PaidDrivingDetail;
use App\Models\CargoTransportDetail;
use App\Models\WaterTransportDetail;
use App\Models\InternationalTripDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'identifier',
        'type',
        'status',
        'note',
        'detailable_id',
        'detailable_type',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
    {
        return $this->hasOne(TripClient::class);
    }

    public function clients()
    {
        return $this->hasMany(TripClient::class);
    }

    public function passenger()
    {
        return $this->hasOneThrough(
            Passenger::class,
            TripClient::class,
            'trip_id',
            'id',
            'id',
            'client_id'
        )->where('client_type', Passenger::class);
    }

    public function cargo()
    {
        return $this->hasOne(TripCargo::class);
    }

    public function cargos()
    {
        return $this->hasMany(TripCargo::class);
    }

    public function reviews()
    {
        return $this->hasMany(TripReview::class);
    }

    public function lostAndFounds()
    {
        return $this->hasMany(LostAndFound::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Polymorphic relationship to trip details
    public function detailable()
    {
        return $this->morphTo();
    }

    // Legacy method for backward compatibility
    public function details()
    {
        return $this->detailable();
    }

    public function getAvailableSeatsAttribute()
    {
        if (!in_array($this->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
            return null;
        }

        $totalSeats = $this->detailable ? $this->detailable->total_seats : 0;
        $bookedSeats = $this->clients()->sum('number_of_seats');

        return max(0, $totalSeats - $bookedSeats);
    }
}