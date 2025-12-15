<?php
namespace App\Support\Enum;
class Roles
{
  const SUPER_ADMIN = 'super_admin';
  const ADMIN = 'admin';

  public static function all($translated = false):array
  {
    return [
      self::SUPER_ADMIN => $translated ? __('constants.super_admin') : self::SUPER_ADMIN,
      self::ADMIN => $translated ? __('constants.admin') : self::ADMIN,
    ];
  }

  public static function lists2(): array
  {
    return [
      self::SUPER_ADMIN => app()->isLocale('ar') ? 'سوبر أدمن' : 'Super Admin',
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
    ];
  }

  public static function get_name(string $role):string
  {
    return self::all(true)[$role];
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
