<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Documentation extends Model
{
  use HasTranslations;

  public array $translatable = [
    'value',
  ];

  protected $fillable = [
    'key',
    'value',
  ];
}
