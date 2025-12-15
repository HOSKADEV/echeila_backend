<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostAndFound extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGE = 'image';

    protected $fillable = [
        'passenger_id',
        'description',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();
    }

    // Relationships

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}