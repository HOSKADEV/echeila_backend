<?php

namespace App\Constants;

class CancellationReason
{
    // Driver reasons
    const PASSENGER_NO_SHOW      = 'passenger_no_show';
    const WRONG_PICKUP_LOCATION  = 'wrong_pickup_location';
    const VEHICLE_MALFUNCTION    = 'vehicle_malfunction';
    const TRAFFIC                = 'traffic';
    const EMERGENCY              = 'emergency';

    // Passenger reasons
    const DRIVER_LATE            = 'driver_late';
    const CHANGED_MIND           = 'changed_mind';
    const FOUND_ANOTHER_WAY      = 'found_another_way';
    const UNSUITABLE_PRICE       = 'unsuitable_price';
    const DRIVER_NO_CONTACT      = 'driver_no_contact';

    // Shared
    const OTHER                  = 'other';

    public static function driverReasons(): array
    {
        return [
            self::PASSENGER_NO_SHOW,
            self::WRONG_PICKUP_LOCATION,
            self::VEHICLE_MALFUNCTION,
            self::TRAFFIC,
            self::EMERGENCY,
            self::OTHER,
        ];
    }

    public static function passengerReasons(): array
    {
        return [
            self::DRIVER_LATE,
            self::CHANGED_MIND,
            self::FOUND_ANOTHER_WAY,
            self::UNSUITABLE_PRICE,
            self::DRIVER_NO_CONTACT,
            self::OTHER,
        ];
    }

    public static function all(): array
    {
        return array_unique(array_merge(self::driverReasons(), self::passengerReasons()));
    }

    public static function translated(): array
    {
        return array_combine(
            self::all(),
            array_map(fn(string $r) => __('trip.cancellation_reasons.' . $r), self::all())
        );
    }

    public static function get_name(string $reason): string
    {
        return __('trip.cancellation_reasons.' . $reason);
    }
}
