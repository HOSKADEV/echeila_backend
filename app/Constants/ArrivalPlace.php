<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class ArrivalPlace
{
    const MAURITANIA = 'mauritania';
    const SPAIN = 'spain';

    public static function all(): array
    {
        return [
            self::MAURITANIA,
            self::SPAIN,
        ];
    }

    public static function all2(): array
    {
        return [
            self::MAURITANIA => __('constants.mauritania'),
            self::SPAIN => __('constants.spain'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::MAURITANIA => 'warning',
            self::SPAIN => 'info',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $place): string
    {
        return self::collection()->get($place);
    }

    public static function get_name(string $place): string
    {
        return self::all2()[$place];
    }

    public static function get_color(string $place): string
    {
        return self::colors()[$place];
    }

    public static function default(): string
    {
        return self::MAURITANIA;
    }
}