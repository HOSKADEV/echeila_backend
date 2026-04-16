<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TripCargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'cargo_id',
        'total_fees',
        'payment_method',
        'is_paid',
    ];

    protected $casts = [
        'total_fees' => 'decimal:2',
        'is_paid'    => 'boolean',
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
