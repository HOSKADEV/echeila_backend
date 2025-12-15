<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class LostAndFoundStatus
{
    const FOUND = 'found';
    const RETURNED = 'returned';

    public static function all(): array
    {
        return [
            self::FOUND,
            self::RETURNED,
        ];
    }

    public static function all2(): array
    {
        return [
            self::FOUND => __('constants.found'),
            self::RETURNED => __('constants.returned'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::FOUND => 'info',
            self::RETURNED => 'warning',
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
        return self::FOUND;
    }
}