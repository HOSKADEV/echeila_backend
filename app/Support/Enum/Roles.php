<?php
namespace App\Support\Enum;
class Roles
{
  const SUPER_ADMIN = 'super_admin';
  const ADMIN = 'admin';

  public static function all(): array
  {
    return [
      self::SUPER_ADMIN,
      self::ADMIN,
    ];
  }

  public static function translated(): array
  {
    return [
      self::SUPER_ADMIN => __('constants.super_admin'),
      self::ADMIN => __('constants.admin'),
    ];
  }

  public static function lists2(): array
  {
    return [
      self::SUPER_ADMIN => app()->isLocale('ar') ? 'سوبر أدمن' : 'Super Admin',
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
    ];
  }

  public static function get_name(string $role): string
  {
    return self::translated()[$role];
  }

  public static function get_color(string $role):string
  {
    return [
      self::SUPER_ADMIN => 'warning',
      self::ADMIN => 'info',
    ][$role];
  }

  public static function default():string
  {
    return self::ADMIN;
  }
}
