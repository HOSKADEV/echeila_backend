<?php

namespace App\Models;

use App\Constants\TripType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'trip_type',
    ];

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}