<?php

namespace App\Providers;

use App\Services\MenuBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->addSidebarMenuItems();
        $menuData = json_decode(json_encode([
            'verticalMenu' => MenuBuilder::get(),
            'horizontalMenu' => MenuBuilder::get(),
        ]));
        $this->app->make('view')->share('menuData', $menuData);
    }

    /**
     * Add sidebar menu items.
     */
    protected function addSidebarMenuItems(): void
    {
        MenuBuilder::add(
            name: 'dashboard',
            slug: 'dashboard',
            route: 'dashboard',
            icon: 'bx bx-home-circle',
        );
        // MenuBuilder::header('Users & Roles');
/*         MenuBuilder::add(
            name: 'roles-permissions',
            slug: ['roles', 'permissions'],
            icon: 'bx bx-shield',
            permission: ['manage_roles', 'manage_permissions'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'roles',
                    slug: 'roles',
                    route: 'roles.index',
                    permission: ['manage_roles']
                ),
                MenuBuilder::submenu(
                    name: 'permissions',
                    slug: 'permissions',
                    route: 'permissions.index',
                    permission: ['manage_permissions']
                ),
            ]
        ); */

        MenuBuilder::add(
            name: 'admins',
            slug: 'admins',
            //route: 'admins.index',
            icon: 'bx bxs-user',
            permission: ['admin_index', 'adminAction_index'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'admins',
                    slug: 'admins',
                    route: 'admins.index',
                    permission: ['admin_index'],
                ),
                MenuBuilder::submenu(
                    name: 'admin-actions',
                    slug: 'admin-actions',
                    route: 'admin-actions.index',
                    permission: ['adminAction_index'],
                ),
            ]
        );

        MenuBuilder::add(
            name: 'users',
            slug: ['passengers', 'drivers', 'federations'],
            icon: 'bx bx-user',
            permission: ['passenger_index', 'driver_index', 'federation_index'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'passengers',
                    slug: 'passengers',
                    route: 'passengers.index',
                    permission: ['passenger_index']
                ),
                MenuBuilder::submenu(
                    name: 'drivers',
                    slug: 'drivers',
                    route: 'drivers.index',
                    permission: ['driver_index']
                ),
                MenuBuilder::submenu(
                    name: 'federations',
                    slug: 'federations',
                    route: 'federations.index',
                    permission: ['federation_index']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'wilayas',
            slug: ['wilayas', 'seat-prices'],
            icon: 'bx bx-map',
            permission: ['wilaya_index', 'seatPrice_index'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'wilayas',
                    slug: 'wilayas',
                    route: 'wilayas.index',
                    permission: ['wilaya_index']
                ),
                MenuBuilder::submenu(
                    name: 'seat-prices',
                    slug: 'seat-prices',
                    route: 'seat-prices.index',
                    permission: ['seatPrice_index']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'vehicles',
            slug: ['brands', 'vehicle-models', 'colors'],
            icon: 'bx bx-car',
            permission: ['brand_index', 'vehicleModel_index', 'color_index'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'brands',
                    slug: 'brands',
                    route: 'brands.index',
                    permission: ['brand_index']
                ),
                MenuBuilder::submenu(
                    name: 'vehicle-models',
                    slug: 'vehicle-models',
                    route: 'vehicle-models.index',
                    permission: ['vehicleModel_index']
                ),
                MenuBuilder::submenu(
                    name: 'colors',
                    slug: 'colors',
                    route: 'colors.index',
                    permission: ['color_index']
                ),
            ]
        );

        MenuBuilder::add(
            name: 'trips',
            slug: ['admin/trips/all', 'admin/trips/taxi_ride', 'admin/trips/car_rescue', 'admin/trips/cargo_transport', 'admin/trips/water_transport', 'admin/trips/paid_driving', 'admin/trips/mrt_trip', 'admin/trips/esp_trip'],
            icon: 'bx bx-trip',
            permission: ['allTrips_index', 'taxiRide_index', 'carRescue_index', 'cargoTransport_index', 'waterTransport_index', 'paidDriving_index', 'mrtTrip_index', 'espTrip_index'],
            submenu: [
                MenuBuilder::submenu(
                    name: 'all_trips',
                    slug: 'admin/trips/all',
                    url: 'admin/trips/all',
                    permission: ['allTrips_index'],
                ),
                MenuBuilder::submenu(
                    name: 'taxi_rides',
                    slug: 'admin/trips/taxi_ride',
                    url: 'admin/trips/taxi_ride',
                    permission: ['taxiRide_index'],
                ),
                MenuBuilder::submenu(
                    name: 'car_rescues',
                    slug: 'admin/trips/car_rescue',
                    url: 'admin/trips/car_rescue',
                    permission: ['carRescue_index'],
                ),
                MenuBuilder::submenu(
                    name: 'cargo_transports',
                    slug: 'admin/trips/cargo_transport',
                    url: 'admin/trips/cargo_transport',
                    permission: ['cargoTransport_index'],
                ),
                MenuBuilder::submenu(
                    name: 'water_transports',
                    slug: 'admin/trips/water_transport',
                    url: 'admin/trips/water_transport',
                    permission: ['waterTransport_index'],
                ),
                MenuBuilder::submenu(
                    name: 'paid_drivings',
                    slug: 'admin/trips/paid_driving',
                    url: 'admin/trips/paid_driving',
                    permission: ['paidDriving_index'],
                ),
                MenuBuilder::submenu(
                    name: 'mrt_trips',
                    slug: 'admin/trips/mrt_trip',
                    url: 'admin/trips/mrt_trip',
                    permission: ['mrtTrip_index'],
                ),
                MenuBuilder::submenu(
                    name: 'esp_trips',
                    slug: 'admin/trips/esp_trip',
                    url: 'admin/trips/esp_trip',
                    permission: ['espTrip_index'],
                ),
            ]
        );

        MenuBuilder::add(
            name: 'lost-and-founds',
            slug: 'lost-and-founds',
            route: 'lost-and-founds.index',
            icon: 'bx bx-search',
            permission: ['lostAndFound_index'],
        );

    }
}
