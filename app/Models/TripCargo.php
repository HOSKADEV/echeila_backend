<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TripCargo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGES = 'images';

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGES);
    }
}
