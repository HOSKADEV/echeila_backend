<?php

namespace App\Constants;

class NotificationMessages
{
    // Admin notifications
    const ADMIN_NOTIFICATION = 'admin_notification';
    
    // Update notifications
    const SYSTEM_UPDATE = 'system_update';
    
    // User status notifications
    const USER_ACTIVATED = 'user_activated';
    const USER_DEACTIVATED = 'user_deactivated';
    const USER_BANNED = 'user_banned';
    
    // Driver status notifications
    const DRIVER_PENDING = 'driver_pending';
    const DRIVER_APPROVED = 'driver_approved';
    const DRIVER_DENIED = 'driver_denied';
    
    // Trip status notifications
    const TRIP_PENDING = 'trip_pending';
    const TRIP_ACCEPTED = 'trip_accepted';
    const TRIP_ONGOING = 'trip_ongoing';
    const TRIP_COMPLETED = 'trip_completed';
    const TRIP_CANCELLED = 'trip_cancelled';
    
    // Transaction type notifications
    const TRANSACTION_RESERVATION = 'transaction_reservation';
    const TRANSACTION_REFUND = 'transaction_refund';
    const TRANSACTION_DEPOSIT = 'transaction_deposit';
    const TRANSACTION_WITHDRAW = 'transaction_withdraw';
    const TRANSACTION_SUBSCRIPTION = 'transaction_subscription';
    const TRANSACTION_SERVICE = 'transaction_service';

    public static function titles(?string $locale = null): array
    {
        return [
            
            // Admin
            self::ADMIN_NOTIFICATION => __('messages.titles.admin_notification', [], $locale),
            
            // Update
            self::SYSTEM_UPDATE => __('messages.titles.system_update', [], $locale),
            
            // User statuses
            self::USER_ACTIVATED => __('messages.titles.user_activated', [], $locale),
            self::USER_DEACTIVATED => __('messages.titles.user_deactivated', [], $locale),
            self::USER_BANNED => __('messages.titles.user_banned', [], $locale),
            
            // Driver statuses
            self::DRIVER_PENDING => __('messages.titles.driver_pending', [], $locale),
            self::DRIVER_APPROVED => __('messages.titles.driver_approved', [], $locale),
            self::DRIVER_DENIED => __('messages.titles.driver_denied', [], $locale),
            
            // Trip statuses
            self::TRIP_PENDING => __('messages.titles.trip_pending', [], $locale),
            self::TRIP_ACCEPTED => __('messages.titles.trip_accepted', [], $locale),
            self::TRIP_ONGOING => __('messages.titles.trip_ongoing', [], $locale),
            self::TRIP_COMPLETED => __('messages.titles.trip_completed', [], $locale),
            self::TRIP_CANCELLED => __('messages.titles.trip_cancelled', [], $locale),
            
            // Transaction types
            self::TRANSACTION_RESERVATION => __('messages.titles.transaction_reservation', [], $locale),
            self::TRANSACTION_REFUND => __('messages.titles.transaction_refund', [], $locale),
            self::TRANSACTION_DEPOSIT => __('messages.titles.transaction_deposit', [], $locale),
            self::TRANSACTION_WITHDRAW => __('messages.titles.transaction_withdraw', [], $locale),
            self::TRANSACTION_SUBSCRIPTION => __('messages.titles.transaction_subscription', [], $locale),
            self::TRANSACTION_SERVICE => __('messages.titles.transaction_service', [], $locale),
        ];
    }

    public static function bodies(?string $locale = null, array $replace = []): array
    {
        return [
            
            // Admin
            self::ADMIN_NOTIFICATION => __('messages.bodies.admin_notification', $replace, $locale),
            
            // Update
            self::SYSTEM_UPDATE => __('messages.bodies.system_update', $replace, $locale),
            
            // User statuses
            self::USER_ACTIVATED => __('messages.bodies.user_activated', $replace, $locale),
            self::USER_DEACTIVATED => __('messages.bodies.user_deactivated', $replace, $locale),
            self::USER_BANNED => __('messages.bodies.user_banned', $replace, $locale),
            
            // Driver statuses
            self::DRIVER_PENDING => __('messages.bodies.driver_pending', $replace, $locale),
            self::DRIVER_APPROVED => __('messages.bodies.driver_approved', $replace, $locale),
            self::DRIVER_DENIED => __('messages.bodies.driver_denied', $replace, $locale),
            
            // Trip statuses
            self::TRIP_PENDING => __('messages.bodies.trip_pending', $replace, $locale),
            self::TRIP_ACCEPTED => __('messages.bodies.trip_accepted', $replace, $locale),
            self::TRIP_ONGOING => __('messages.bodies.trip_ongoing', $replace, $locale),
            self::TRIP_COMPLETED => __('messages.bodies.trip_completed', $replace, $locale),
            self::TRIP_CANCELLED => __('messages.bodies.trip_cancelled', $replace, $locale),
            
            // Transaction types
            self::TRANSACTION_RESERVATION => __('messages.bodies.transaction_reservation', $replace, $locale),
            self::TRANSACTION_REFUND => __('messages.bodies.transaction_refund', $replace, $locale),
            self::TRANSACTION_DEPOSIT => __('messages.bodies.transaction_deposit', $replace, $locale),
            self::TRANSACTION_WITHDRAW => __('messages.bodies.transaction_withdraw', $replace, $locale),
            self::TRANSACTION_SUBSCRIPTION => __('messages.bodies.transaction_subscription', $replace, $locale),
            self::TRANSACTION_SERVICE => __('messages.bodies.transaction_service', $replace, $locale),
        ];
    }

    public static function customNotifications(): array
    {
        return [
            self::ADMIN_NOTIFICATION,
            self::SYSTEM_UPDATE,
        ];
    }

    public static function title(string $key, string $locale): string
    {
        $titles = self::titles($locale);
        return $titles[$key] ?? 'Notification';
    }

    public static function body(string $key, string $locale, array $replace = []): string
    {
        $bodies = self::bodies($locale, $replace);
        return $bodies[$key] ?? 'You have a new notification';
    }
}