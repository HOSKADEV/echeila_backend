<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneVerification extends Model
{
    protected $fillable = [
      'phone_number',
      'verified_at',
      'expires_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'phone_number', 'phone');
    }

    public function getIsValidAttribute(): bool
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    public static function isVerified(string $phoneNumber): bool
    {
        $verification = self::where('phone_number', $phoneNumber)->latest()->first();

        return $verification && $verification->is_valid;
    }
}
