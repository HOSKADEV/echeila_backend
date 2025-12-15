<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;


class NotificationController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $user = auth()->user();

      $notifications = $user->notifications()->latest()->paginate(10);
      
      return $this->successResponse(
        data: NotificationResource::collection($notifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function unread(Request $request)
  {
    try {
      $user = auth()->user();

      $unreadNotifications = $user->unreadNotifications()->latest()->paginate(10);

      return $this->successResponse(
        data: NotificationResource::collection($unreadNotifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function markAsRead($id, Request $request)
  {
    try {
      $user = auth()->user();

      $notification = $user->notifications()->findOrFail($id);

      $notification->markAsRead();
      
      return $this->successResponse(new NotificationResource($notification));
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }
}
