<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class UserStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const BANNED = 'banned';

    public static function all():array
    {
        return [
          self::ACTIVE,
          //self::INACTIVE,
          self::BANNED,
        ];
    }

    public static function all2():array
    {
        return [
          self::ACTIVE => __('constants.active'),
          self::INACTIVE => __('constants.inactive'),
          self::BANNED => __('constants.banned'),
        ];
    }

    public static function colors(): array
    {
        return [
          self::ACTIVE => 'success',
          self::INACTIVE => 'warning',
          self::BANNED => 'danger',
        ];
    }

    public static function collection():Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $gender):string
    {
        return self::collection()->get($gender);
    }

    public static function get_name(string $status):string
    {
        return self::all2()[$status];
    }

    public static function get_color(string $status):string
    {
        return self::colors()[$status];
    }

    public static function default():string
    {
        return self::ACTIVE;
    }

}
