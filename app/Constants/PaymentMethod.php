<?php

namespace App\Constants;

class PaymentMethod
{
    const WALLET = 'wallet';
    const CASH = 'cash';

    public static function all(): array
    {
        return [
            self::WALLET,
            self::CASH,
        ];
    }
}
