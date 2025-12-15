<?php

namespace App\Services;

class MenuBuilder
{
  // Static array to hold the menu items
  protected static array $menu = [];

  /**
   * Add a new menu item to the menu.
   *
   * @param string $name The name of the menu item
   * @param array|string $slug The slug(s) for the menu item
   * @param string|null $route The route for the menu item
   * @param string|null $url The URL for the menu item (alternative to route)
   * @param string|null $icon The icon class for the menu item
   * @param array|string|null $permission The permission(s) required to access this item
   * @param array $submenu The submenu items, if any
   */
  public static function add(
    string $name,
    array|string $slug,
    ?string $route = null,
    ?string $url = null,
    ?string $icon = 'bx bx-circle', // Default icon class (without 'menu-icon tf-icons')
    array|string|null $permission = null,
    array $submenu = []
  ): void {
    $item = [
      'name' => $name,
      'icon' => 'menu-icon tf-icons ' . $icon, // Add the default prefix
      'slug' => $slug,
    ];

    // If permission is provided, add it to the item
    if ($permission) {
      $item['permission'] = $permission;
    }

    // If route is provided, add it to the item (route takes precedence over url)
    if ($route) {
      $item['route'] = $route;
    } elseif ($url) {
      // If url is provided and route is not, add url to the item
      $item['url'] = $url;
    }

    // If submenu is provided, add it to the item
    if (!empty($submenu)) {
      $item['submenu'] = $submenu;
    }

    // Add the item to the menu
    self::$menu[] = $item;
  }

  /**
   * Create a submenu item.
   *
   * @param string $name The name of the submenu item
   * @param string $slug The slug for the submenu item
   * @param string|null $route The route for the submenu item
   * @param string|null $url The URL for the submenu item (alternative to route)
   * @param string|null $icon The icon class for the submenu item
   * @param string|array|null $permission The permission required to access the submenu
   * @return array The submenu item
   */
  public static function submenu(
    string $name,
    string $slug,
    ?string $route = null,
    ?string $url = null,
    ?string $icon = null, // Default icon class (without 'menu-icon tf-icons')
    string|array|null $permission = null
  ): array {
    $sub = [
      'name' => $name,
      'slug' => $slug,
      'icon' => $icon? 'menu-icon tf-icons ' . $icon : '', // Add the default prefix
    ];

    // If route is provided, add it to the submenu (route takes precedence over url)
    if ($route) {
      $sub['route'] = $route;
    } elseif ($url) {
      // If url is provided and route is not, add url to the submenu
      $sub['url'] = $url;
    }

    // If permission is provided, add it to the submenu
    if ($permission) {
      $sub['permission'] = $permission;
    }

    return $sub;
  }

  /**
   * Add a header to the menu.
   *
   * @param string $title The title of the header
   */
  public static function header(string $title): void
  {
    self::$menu[] = ['menuHeader' => $title];
  }

  /**
   * Retrieve the current menu.
   *
   * @return array The current menu items
   */
  public static function get(): array
  {
    return self::$menu;
  }
}
