<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Notification */
class NotificationResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'type' => class_basename($this->type),
      'key' => $this->data['key'] ?? null,
      'title' => match (app()->getLocale()) {
            'fr' => $this->data['title']['fr'] ?? '',
            'ar' => $this->data['title']['ar'] ?? '',
            default => $this->data['title']['en'] ?? '',
        },
      'body' => match (app()->getLocale()) {
            'fr' => $this->data['body']['fr'] ?? '',
            'ar' => $this->data['body']['ar'] ?? '',
            default => $this->data['body']['en'] ?? '',
        },
      'data' => $this->data['data'] ?? [],
      'read' => !is_null($this->read_at),
      'created_at' => $this->created_at->toDateTimeString(),
    ];
  }
}
