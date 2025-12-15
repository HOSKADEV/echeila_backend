<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class Direction
{
    const FROM = 'from';
    const TO = 'to';

    public static function all(): array
    {
        return [
            self::FROM,
            self::TO,
        ];
    }

    public static function all2(): array
    {
        return [
            self::FROM => __('constants.from'),
            self::TO => __('constants.to'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::FROM => 'blue',
            self::TO => 'teal',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $direction): string
    {
        return self::collection()->get($direction);
    }

    public static function get_name(string $direction): string
    {
        return self::all2()[$direction];
    }

    public static function get_color(string $direction): string
    {
        return self::colors()[$direction];
    }

    public static function default(): string
    {
        return self::FROM;
    }
}