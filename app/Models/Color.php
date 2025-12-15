<?php

namespace App\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory, HasGoogleTranslationTrait;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'name' => 'json',
    ];

    protected array $translatable = [
        'name',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}