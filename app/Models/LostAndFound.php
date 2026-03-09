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
        'user_id',
        'description',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}