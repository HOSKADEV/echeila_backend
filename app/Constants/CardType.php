<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class CardType
{
    const NATIONAL_ID = 'national_id';
    const DRIVING_LICENSE = 'driving_license';

    public static function all(): array
    {
        return [
            self::NATIONAL_ID,
            self::DRIVING_LICENSE,
        ];
    }

    public static function all2(): array
    {
        return [
            self::NATIONAL_ID => __('constants.national_id'),
            self::DRIVING_LICENSE => __('constants.driving_license'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::NATIONAL_ID => 'warning',
            self::DRIVING_LICENSE => 'info',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $type): string
    {
        return self::collection()->get($type);
    }

    public static function get_name(string $type): string
    {
        return self::all2()[$type];
    }

    public static function get_color(string $type): string
    {
        return self::colors()[$type];
    }

    public static function default(): string
    {
        return self::NATIONAL_ID;
    }
}