<?php

namespace App\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory, HasGoogleTranslationTrait;

    protected $table = 'models';
    
    protected $fillable = [
        'brand_id',
        'name',
    ];

    protected $casts = [
        'name' => 'json',
    ];

    protected array $translatable = [
        'name',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'model_id');
    }
}