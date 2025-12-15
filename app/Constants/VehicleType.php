<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class VehicleType
{
    const CAR = 'car';
    const TRUCK = 'truck';

    public static function all(): array
    {
        return [
            self::CAR,
            self::TRUCK,
        ];
    }

    public static function all2(): array
    {
        return [
            self::CAR => __('constants.car'),
            self::TRUCK => __('constants.truck'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::CAR => 'warning',
            self::TRUCK => 'info',
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
        return self::CAR;
    }
}