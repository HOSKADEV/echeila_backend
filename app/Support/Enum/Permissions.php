<?php

namespace App\Support\Enum;

class Permissions
{
    // Roles & Permissions
    const MANAGE_ROLES = 'manage_roles';
    const MANAGE_PERMISSIONS = 'manage_permissions';
    const MANAGE_SETTINGS = 'manage_settings';
    const MANAGE_NOTIFICATIONS = 'manage_notifications';
    const MANAGE_DOCUMENTATIONS = 'manage_documentations';

    // Admin permissions
    const ADMIN_INDEX = 'admin_index';
    const ADMIN_CREATE = 'admin_create';
    const ADMIN_UPDATE = 'admin_update';
    const ADMIN_DELETE = 'admin_delete';
    const ADMIN_ACTION_INDEX = 'adminAction_index';

    // Passenger permissions
    const PASSENGER_INDEX = 'passenger_index';
    const PASSENGER_SHOW = 'passenger_show';
    const PASSENGER_CHANGE_USER_STATUS = 'passenger_changeUserStatus';
    const PASSENGER_CHARGE_WALLET = 'passenger_chargeWallet';
    const PASSENGER_WITHDRAW_SUM = 'passenger_withdrawSum';

    // Driver permissions
    const DRIVER_INDEX = 'driver_index';
    const DRIVER_SHOW = 'driver_show';
    const DRIVER_CHANGE_STATUS = 'driver_changeStatus';
    const DRIVER_CHANGE_USER_STATUS = 'driver_changeUserStatus';
    const DRIVER_CHARGE_WALLET = 'driver_chargeWallet';
    const DRIVER_WITHDRAW_SUM = 'driver_withdrawSum';
    const DRIVER_PURCHASE_SUBSCRIPTION = 'driver_purchaseSubscription';

    // Federation permissions
    const FEDERATION_INDEX = 'federation_index';
    const FEDERATION_SHOW = 'federation_show';
    const FEDERATION_CREATE = 'federation_create';
    const FEDERATION_CHANGE_USER_STATUS = 'federation_changeUserStatus';

    // Wilaya permissions
    const WILAYA_INDEX = 'wilaya_index';
    const WILAYA_CREATE = 'wilaya_create';
    const WILAYA_UPDATE = 'wilaya_update';
    const WILAYA_DELETE = 'wilaya_delete';

    // Seat Price permissions
    const SEAT_PRICE_INDEX = 'seatPrice_index';
    const SEAT_PRICE_CREATE = 'seatPrice_create';
    const SEAT_PRICE_UPDATE = 'seatPrice_update';
    const SEAT_PRICE_DELETE = 'seatPrice_delete';

    // Brand permissions
    const BRAND_INDEX = 'brand_index';
    const BRAND_CREATE = 'brand_create';
    const BRAND_UPDATE = 'brand_update';
    const BRAND_DELETE = 'brand_delete';

    // Vehicle Model permissions
    const VEHICLE_MODEL_INDEX = 'vehicleModel_index';
    const VEHICLE_MODEL_CREATE = 'vehicleModel_create';
    const VEHICLE_MODEL_UPDATE = 'vehicleModel_update';
    const VEHICLE_MODEL_DELETE = 'vehicleModel_delete';

    // Color permissions
    const COLOR_INDEX = 'color_index';
    const COLOR_CREATE = 'color_create';
    const COLOR_UPDATE = 'color_update';
    const COLOR_DELETE = 'color_delete';

    // Lost and Found permissions
    const LOST_AND_FOUND_INDEX = 'lostAndFound_index';
    const LOST_AND_FOUND_SHOW = 'lostAndFound_show';
    const LOST_AND_FOUND_UPDATE = 'lostAndFound_update';
    const LOST_AND_FOUND_DELETE = 'lostAndFound_delete';
    const LOST_AND_FOUND_CHANGE_STATUS = 'lostAndFound_changeStatus';

    // Trip permissions
    const ALL_TRIPS_INDEX = 'allTrips_index';
    const TAXI_RIDE_INDEX = 'taxiRide_index';
    const CAR_RESCUE_INDEX = 'carRescue_index';
    const CARGO_TRANSPORT_INDEX = 'cargoTransport_index';
    const WATER_TRANSPORT_INDEX = 'waterTransport_index';
    const PAID_DRIVING_INDEX = 'paidDriving_index';
    const MRT_TRIP_INDEX = 'mrtTrip_index';
    const ESP_TRIP_INDEX = 'espTrip_index';

    const TAXI_RIDE_SHOW = 'taxiRide_show';
    const CAR_RESCUE_SHOW = 'carRescue_show';
    const CARGO_TRANSPORT_SHOW = 'cargoTransport_show';
    const WATER_TRANSPORT_SHOW = 'waterTransport_show';
    const PAID_DRIVING_SHOW = 'paidDriving_show';
    const MRT_TRIP_SHOW = 'mrtTrip_show';
    const ESP_TRIP_SHOW = 'espTrip_show';

    public static function lists(): array
    {
        return [
            self::MANAGE_ROLES => self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS => self::MANAGE_PERMISSIONS,
            self::MANAGE_SETTINGS => self::MANAGE_SETTINGS,
            self::MANAGE_NOTIFICATIONS => self::MANAGE_NOTIFICATIONS,
            self::MANAGE_DOCUMENTATIONS => self::MANAGE_DOCUMENTATIONS,
            
            // Admin
            self::ADMIN_INDEX => self::ADMIN_INDEX,
            self::ADMIN_CREATE => self::ADMIN_CREATE,
            self::ADMIN_UPDATE => self::ADMIN_UPDATE,
            self::ADMIN_DELETE => self::ADMIN_DELETE,
            self::ADMIN_ACTION_INDEX => self::ADMIN_ACTION_INDEX,
            
            // Passenger
            self::PASSENGER_INDEX => self::PASSENGER_INDEX,
            self::PASSENGER_SHOW => self::PASSENGER_SHOW,
            self::PASSENGER_CHANGE_USER_STATUS => self::PASSENGER_CHANGE_USER_STATUS,
            self::PASSENGER_CHARGE_WALLET => self::PASSENGER_CHARGE_WALLET,
            self::PASSENGER_WITHDRAW_SUM => self::PASSENGER_WITHDRAW_SUM,
            
            // Driver
            self::DRIVER_INDEX => self::DRIVER_INDEX,
            self::DRIVER_SHOW => self::DRIVER_SHOW,
            self::DRIVER_CHANGE_STATUS => self::DRIVER_CHANGE_STATUS,
            self::DRIVER_CHANGE_USER_STATUS => self::DRIVER_CHANGE_USER_STATUS,
            self::DRIVER_CHARGE_WALLET => self::DRIVER_CHARGE_WALLET,
            self::DRIVER_WITHDRAW_SUM => self::DRIVER_WITHDRAW_SUM,
            self::DRIVER_PURCHASE_SUBSCRIPTION => self::DRIVER_PURCHASE_SUBSCRIPTION,
            
            // Federation
            self::FEDERATION_INDEX => self::FEDERATION_INDEX,
            self::FEDERATION_SHOW => self::FEDERATION_SHOW,
            self::FEDERATION_CREATE => self::FEDERATION_CREATE,
            self::FEDERATION_CHANGE_USER_STATUS => self::FEDERATION_CHANGE_USER_STATUS,
            
            // Wilaya
            self::WILAYA_INDEX => self::WILAYA_INDEX,
            self::WILAYA_CREATE => self::WILAYA_CREATE,
            self::WILAYA_UPDATE => self::WILAYA_UPDATE,
            self::WILAYA_DELETE => self::WILAYA_DELETE,
            
            // Seat Price
            self::SEAT_PRICE_INDEX => self::SEAT_PRICE_INDEX,
            self::SEAT_PRICE_CREATE => self::SEAT_PRICE_CREATE,
            self::SEAT_PRICE_UPDATE => self::SEAT_PRICE_UPDATE,
            self::SEAT_PRICE_DELETE => self::SEAT_PRICE_DELETE,
            
            // Brand
            self::BRAND_INDEX => self::BRAND_INDEX,
            self::BRAND_CREATE => self::BRAND_CREATE,
            self::BRAND_UPDATE => self::BRAND_UPDATE,
            self::BRAND_DELETE => self::BRAND_DELETE,
            
            // Vehicle Model
            self::VEHICLE_MODEL_INDEX => self::VEHICLE_MODEL_INDEX,
            self::VEHICLE_MODEL_CREATE => self::VEHICLE_MODEL_CREATE,
            self::VEHICLE_MODEL_UPDATE => self::VEHICLE_MODEL_UPDATE,
            self::VEHICLE_MODEL_DELETE => self::VEHICLE_MODEL_DELETE,
            
            // Color
            self::COLOR_INDEX => self::COLOR_INDEX,
            self::COLOR_CREATE => self::COLOR_CREATE,
            self::COLOR_UPDATE => self::COLOR_UPDATE,
            self::COLOR_DELETE => self::COLOR_DELETE,
            
            // Lost and Found
            self::LOST_AND_FOUND_INDEX => self::LOST_AND_FOUND_INDEX,
            self::LOST_AND_FOUND_SHOW => self::LOST_AND_FOUND_SHOW,
            self::LOST_AND_FOUND_UPDATE => self::LOST_AND_FOUND_UPDATE,
            self::LOST_AND_FOUND_DELETE => self::LOST_AND_FOUND_DELETE,
            self::LOST_AND_FOUND_CHANGE_STATUS => self::LOST_AND_FOUND_CHANGE_STATUS,
            
            // Trips
            self::ALL_TRIPS_INDEX => self::ALL_TRIPS_INDEX,
            self::TAXI_RIDE_INDEX => self::TAXI_RIDE_INDEX,
            self::CAR_RESCUE_INDEX => self::CAR_RESCUE_INDEX,
            self::CARGO_TRANSPORT_INDEX => self::CARGO_TRANSPORT_INDEX,
            self::WATER_TRANSPORT_INDEX => self::WATER_TRANSPORT_INDEX,
            self::PAID_DRIVING_INDEX => self::PAID_DRIVING_INDEX,
            self::MRT_TRIP_INDEX => self::MRT_TRIP_INDEX,
            self::ESP_TRIP_INDEX => self::ESP_TRIP_INDEX,

            self::TAXI_RIDE_SHOW => self::TAXI_RIDE_SHOW,
            self::CAR_RESCUE_SHOW => self::CAR_RESCUE_SHOW,
            self::CARGO_TRANSPORT_SHOW => self::CARGO_TRANSPORT_SHOW,
            self::WATER_TRANSPORT_SHOW => self::WATER_TRANSPORT_SHOW,
            self::PAID_DRIVING_SHOW => self::PAID_DRIVING_SHOW,
            self::MRT_TRIP_SHOW => self::MRT_TRIP_SHOW,
            self::ESP_TRIP_SHOW => self::ESP_TRIP_SHOW,
        ];
    }

    public static function get_permission_slug($permission)
    {
        return __('permissions.' . $permission);
    }

    public static function get_group_name($groupKey)
    {
        return __('permissions.groups.' . $groupKey);
    }

    public static function getPermissionGroups()
    {
        return [
            'system_management' => [
                self::MANAGE_ROLES,
                self::MANAGE_PERMISSIONS,
                self::MANAGE_SETTINGS,
                self::MANAGE_NOTIFICATIONS,
                self::MANAGE_DOCUMENTATIONS,
            ],
            'admin_management' => [
                self::ADMIN_INDEX,
                self::ADMIN_CREATE,
                self::ADMIN_UPDATE,
                self::ADMIN_DELETE,
                self::ADMIN_ACTION_INDEX,
            ],
            'passenger_management' => [
                self::PASSENGER_INDEX,
                self::PASSENGER_SHOW,
                self::PASSENGER_CHANGE_USER_STATUS,
                self::PASSENGER_CHARGE_WALLET,
                self::PASSENGER_WITHDRAW_SUM,
            ],
            'driver_management' => [
                self::DRIVER_INDEX,
                self::DRIVER_SHOW,
                self::DRIVER_CHANGE_STATUS,
                self::DRIVER_CHANGE_USER_STATUS,
                self::DRIVER_CHARGE_WALLET,
                self::DRIVER_WITHDRAW_SUM,
                self::DRIVER_PURCHASE_SUBSCRIPTION,
            ],
            'federation_management' => [
                self::FEDERATION_INDEX,
                self::FEDERATION_SHOW,
                self::FEDERATION_CREATE,
                self::FEDERATION_CHANGE_USER_STATUS,
            ],
            'wilaya_management' => [
                self::WILAYA_INDEX,
                self::WILAYA_CREATE,
                self::WILAYA_UPDATE,
                self::WILAYA_DELETE,
            ],
            'seat_price_management' => [
                self::SEAT_PRICE_INDEX,
                self::SEAT_PRICE_CREATE,
                self::SEAT_PRICE_UPDATE,
                self::SEAT_PRICE_DELETE,
            ],
            'brand_management' => [
                self::BRAND_INDEX,
                self::BRAND_CREATE,
                self::BRAND_UPDATE,
                self::BRAND_DELETE,
            ],
            'vehicle_model_management' => [
                self::VEHICLE_MODEL_INDEX,
                self::VEHICLE_MODEL_CREATE,
                self::VEHICLE_MODEL_UPDATE,
                self::VEHICLE_MODEL_DELETE,
            ],
            'color_management' => [
                self::COLOR_INDEX,
                self::COLOR_CREATE,
                self::COLOR_UPDATE,
                self::COLOR_DELETE,
            ],
            'lost_and_found_management' => [
                self::LOST_AND_FOUND_INDEX,
                self::LOST_AND_FOUND_SHOW,
                self::LOST_AND_FOUND_UPDATE,
                self::LOST_AND_FOUND_DELETE,
                self::LOST_AND_FOUND_CHANGE_STATUS,
            ],
            'trip_management' => [
                self::ALL_TRIPS_INDEX,
                self::TAXI_RIDE_INDEX,
                self::CAR_RESCUE_INDEX,
                self::CARGO_TRANSPORT_INDEX,
                self::WATER_TRANSPORT_INDEX,
                self::PAID_DRIVING_INDEX,
                self::MRT_TRIP_INDEX,            
                self::ESP_TRIP_INDEX,
                self::TAXI_RIDE_SHOW,
                self::CAR_RESCUE_SHOW,
                self::CARGO_TRANSPORT_SHOW,
                self::WATER_TRANSPORT_SHOW,
                self::PAID_DRIVING_SHOW,
                self::MRT_TRIP_SHOW,
                self::ESP_TRIP_SHOW,
            ],
        ];
    }
}
