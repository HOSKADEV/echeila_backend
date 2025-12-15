<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'cargo_id',
        'total_fees',
    ];

    protected $casts = [
        'total_fees' => 'decimal:2',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}