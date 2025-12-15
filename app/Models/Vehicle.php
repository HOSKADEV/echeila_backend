<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGE = 'image';

    const PERMIT = 'permit';

    protected $fillable = [
        'driver_id',
        'model_id',
        'color_id',
        'production_year',
        'plate_number',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();

        $this->addMediaCollection(self::PERMIT)
            ->singleFile();
    }

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
