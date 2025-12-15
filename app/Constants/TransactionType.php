<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class TransactionType
{
    const RESERVATION = 'reservation';
    const REFUND = 'refund';
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const SUBSCRIBTION = 'subscribtion';
    const SERVICE = 'service';

    public static function all(): array
    {
        return [
            self::RESERVATION,
            self::REFUND,
            self::DEPOSIT,
            self::WITHDRAW,
            self::SUBSCRIBTION,
            self::SERVICE,
        ];
    }

    public static function all2(): array
    {
        return [
            self::RESERVATION => __('constants.reservation'),
            self::REFUND => __('constants.refund'),
            self::DEPOSIT => __('constants.deposit'),
            self::WITHDRAW => __('constants.withdraw'),
            self::SUBSCRIBTION => __('constants.subscribtion'),
            self::SERVICE => __('constants.service'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::RESERVATION => 'blue',
            self::REFUND => 'warning',
            self::DEPOSIT => 'info',
            self::WITHDRAW => 'teal',
            self::SUBSCRIBTION => 'purple',
            self::SERVICE => 'red',
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
        return self::RESERVATION;
    }
}