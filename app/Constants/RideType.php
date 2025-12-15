<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class RideType
{
    const SHARED = 'shared';
    const PRIVATE = 'private';

    public static function all(): array
    {
        return [
            self::SHARED,
            self::PRIVATE,
        ];
    }

    public static function all2(): array
    {
        return [
            self::SHARED => __('constants.shared'),
            self::PRIVATE => __('constants.private'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::SHARED => 'info',
            self::PRIVATE => 'warning',
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
        return self::SHARED;
    }
}