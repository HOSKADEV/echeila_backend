<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'reviewer_id',
        'reviewer_type',    // 'App\Models\Passenger' or 'App\Models\Driver'
        'reviewee_id',
        'reviewee_type',    // 'App\Models\Driver' or 'App\Models\Passenger'
        'rating',
        'comment',
    ];

    // Relationships
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function reviewer()
    {
        return $this->morphTo();
    }

    public function reviewee()
    {
        return $this->morphTo();
    }
}