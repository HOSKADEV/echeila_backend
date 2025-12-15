<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_wilaya_id',
        'arrival_wilaya_id',
        'default_seat_price',
    ];

    protected $casts = [
        'default_seat_price' => 'decimal:2',
    ];

    // Relationships
    public function startingWilaya()
    {
        return $this->belongsTo(Wilaya::class, 'starting_wilaya_id');
    }

    public function arrivalWilaya()
    {
        return $this->belongsTo(Wilaya::class, 'arrival_wilaya_id');
    }
}