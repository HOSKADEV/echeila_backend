<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'client_id',
        'client_type',
        'number_of_seats',
        'total_fees',
        'note',
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

    // Polymorphic relationship - client can be passenger or guest
    public function client()
    {
        return $this->morphTo();
    }
}