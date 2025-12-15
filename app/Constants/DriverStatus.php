<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class DriverStatus
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const DENIED = 'denied';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::DENIED,
        ];
    }

    public static function all2(): array
    {
        return [
            self::PENDING => __('constants.pending'),
            self::APPROVED => __('constants.approved'),
            self::DENIED => __('constants.denied'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING => 'info',
            self::APPROVED => 'green',
            self::DENIED => 'warning',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $status): string
    {
        return self::collection()->get($status);
    }

    public static function get_name(string $status): string
    {
        return self::all2()[$status];
    }

    public static function get_color(string $status): string
    {
        return self::colors()[$status];
    }

    public static function default(): string
    {
        return self::PENDING;
    }
}