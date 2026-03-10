<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class DocumentationKey
{
  const about_us = 'about_us';
  const privacy_policy = 'privacy_policy';
  const delete_account = 'delete_account';
  const terms_of_use = 'terms_of_use';
  const safety = 'safety';
  public static function all(): array
  {
    return [
      self::about_us,
      self::privacy_policy,
      self::delete_account,
      self::terms_of_use,
      self::safety,
    ];
  }

  public static function translated(): array
  {
    return [
      self::about_us => __('app.about_us'),
      self::privacy_policy => __('app.privacy_policy'),
      self::delete_account => __('app.delete_account'),
      self::terms_of_use => __('app.terms_of_use'),
      self::safety => __('app.safety'),
    ];
  }

  public static function colors(): array
  {
    return [
      self::about_us => 'info',
      self::privacy_policy => 'secondary',
      self::delete_account => 'danger',
      self::terms_of_use => 'primary',
      self::safety => 'warning',
    ];
  }

  public static function collection():Collection
  {
    return collect(array_combine(self::all(), self::all()));
  }

  public static function get(string $value):string
  {
    return self::collection()->get($value);
  }

  public static function get_name(string $value): string
  {
    return self::translated()[$value];
  }

  public static function get_color(string $value):string
  {
    return self::colors()[$value];
  }

}
