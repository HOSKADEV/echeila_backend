<?php

namespace App\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasGoogleTranslationTrait;

    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'json',
    ];

    protected array $translatable = [
        'name',
    ];

    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }
}