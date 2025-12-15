<?php

namespace App\Models;

use App\Constants\Direction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalTripDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'direction',
        'starting_place',
        'starting_time',
        'arrival_time',
        'total_seats',
        'seat_price',
    ];

    protected $casts = [
        'starting_time' => 'datetime',
        'arrival_time' => 'datetime',
        'seat_price' => 'decimal:2',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

}