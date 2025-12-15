<?php

namespace App\Notifications;

use App\Constants\NotificationMessages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\Aps;
use Kreait\Firebase\Messaging\AndroidNotification;


class NewMessageNotification extends Notification implements ShouldQueue
{
  use Queueable;

  protected $key;
  protected $title;
  protected $body;
  protected array $data;
  protected array $channels;
  protected array $replace;

  public function __construct($key, array $data = [], array $channels = ['database', 'fcm'], array $replace = [], array $custom_title = [], array $custom_body = [])
  {
    $this->key = $key;

    $this->title = in_array($this->key, NotificationMessages::customNotifications())
    ? $custom_title
    : [
      'en' => NotificationMessages::title($key, 'en'),
      'ar' => NotificationMessages::title($key, 'ar'),
      'fr' => NotificationMessages::title($key, 'fr'),
    ];

    $this->body = in_array($this->key, NotificationMessages::customNotifications())
    ? $custom_body
    : [
      'en' => NotificationMessages::body($key, 'en', $replace),
      'ar' => NotificationMessages::body($key, 'ar', $replace),
      'fr' => NotificationMessages::body($key, 'fr', $replace),
    ];

    $this->data = $data;
    $this->channels = $channels;
    $this->replace = $replace;
  }

  public function via($notifiable): array
  {
    $via = [];

    if (in_array('database', $this->channels)) {
      $via[] = 'database';
    }

    if (in_array('fcm', $this->channels) && $notifiable->device_token) {
      $via[] = 'fcm';
    }

    return $via;
  }

  public function toDatabase($notifiable): array
  {
    return [
      'key' => $this->key,
      'title' => $this->title,
      'body' => $this->body,
      'data' => $this->data,
    ];
  }

  public function toFcm($notifiable): CloudMessage
  {
    $locale = app()->getLocale();

    $notification = FirebaseNotification::create(
      $this->title[$locale],
      $this->body[$locale]
    );

    // Android config (يدوي)
    $androidConfig = AndroidConfig::fromArray([
      'priority' => 'high',
      'notification' => [
        'sound' => 'default',
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'channel_id' => 'echeila_channel',
      ],
    ]);

    // iOS (Apns) config (يدوي)
    $apnsConfig = ApnsConfig::fromArray([
      'headers' => [
        'apns-priority' => '10',
      ],
      'payload' => [
        'aps' => [
          'alert' => [
            'title' => $this->title[$locale],
            'body' => $this->body[$locale],
          ],
          'sound' => 'default',
        ],
      ],
    ]);

    return CloudMessage::withTarget('token', $notifiable->device_token)
      ->withNotification($notification)
      ->withData($this->data)
      ->withAndroidConfig($androidConfig)
      ->withApnsConfig($apnsConfig);
  }
}
