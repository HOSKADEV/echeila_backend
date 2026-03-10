<?php
namespace App\Constants;
class UserType
{
  const PASSENGER = 'passenger';
  const DRIVER = 'driver';
  const FEDERATION = 'federation';

  public static function all(): array
  {
    return [
      self::PASSENGER,
      self::DRIVER,
      self::FEDERATION,
    ];
  }

  public static function translated(): array
  {
    return [
      self::PASSENGER => __('constants.passenger'),
      self::DRIVER => __('constants.driver'),
      self::FEDERATION => __('constants.federation'),
    ];
  }

  public static function lists2(): array
  {
    return [
      self::PASSENGER => app()->isLocale('ar') ? 'راكب' : 'Passenger',
      self::DRIVER => app()->isLocale('ar') ? 'سائق' : 'Driver',
      self::FEDERATION => app()->isLocale('ar') ? 'اتحاد' : 'Federation',
    ];
  }

  public static function colors():array
  {
    return [
      self::PASSENGER => 'info',
      self::DRIVER => 'warning',
      self::FEDERATION => 'purple',
    ];
  }

  public static function lists_arabic()
  {
    return [
      self::PASSENGER => 'راكب',
      self::DRIVER => 'سائق',
      self::FEDERATION => 'اتحاد'
    ];
  }

  public static function get_arabic_name(string $type):string
  {
    return self::lists_arabic()[$type];
  }

  public static function get_name(string $type): string
  {
    return self::translated()[$type];
  }

  public static function get_color(string $type):string
  {
    return self::colors()[$type];
  }
}
