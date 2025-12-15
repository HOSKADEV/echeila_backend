<?php

namespace App\Models;

use App\Constants\MalfunctionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRescueDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'breakdown_point_id',
        'delivery_time',
        'malfunction_type',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
    ];

    // Relationships
    public function trip()
    {
        return $this->morphOne(Trip::class, 'detailable');
    }

    public function breakdownPoint()
    {
        return $this->belongsTo(Location::class, 'breakdown_point_id');
    }
}