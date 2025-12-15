<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const FRONT_IMAGE = 'front_image';
    const BACK_IMAGE = 'back_image';

    protected $fillable = [
        'driver_id',
        'type',
        'number',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::FRONT_IMAGE)
            ->singleFile();

        $this->addMediaCollection(self::BACK_IMAGE)
            ->singleFile();
    }

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
