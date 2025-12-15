<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cargo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGES = 'images';

    protected $fillable = [
        'passenger_id',
        'description',
        'weight',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGES);
    }

    public function tripCargos()
    {
        return $this->hasMany(TripCargo::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}