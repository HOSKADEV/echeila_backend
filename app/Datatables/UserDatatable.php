<?php

namespace App\Datatables;

use App\Constants\UserStatus;
use App\Models\User;
use App\Support\Enum\Permissions;
use App\Support\Enum\Roles;
use App\Constants\UserType;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;


class UserDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "name",
      "email",
      "phone",
      "avatar",
      "gender",
      "type",
      "role",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (User $user) {
          return $this
            ->edit(route("users.edit", $user->id), Auth::user()->hasPermissionTo(Permissions::MANAGE_USERS))
            ->delete($user->id, Auth::user()->hasPermissionTo(Permissions::MANAGE_USERS))
            ->make();
        })
        ->addColumn("name", function (User $user) {
          return $this->bold($user->fullname);
        })
        ->addColumn("email", function (User $user) {
          return $user->email;
        })
        ->addColumn("phone", function (User $user) {
          return $user->phone;
        })
        ->addColumn("avatar", function (User $user) {
          return $this->image($user->avatar_url, $user->fullname);
        })
        ->addColumn("type", function (User $user) {
          return $this->badge(UserType::get_name($user->type), UserType::get_color($user->type), 'bx bx-user');
        })
        ->addColumn("role", function (User $user) {
          $role = $user->getRoleNames()->first()? Roles::get_name($user->getRoleNames()->first()) : "-";
          return $this->statuses('secondary', $role);
        })
        ->addColumn("status", function (User $user) {
          return $this->statuses(UserStatus::get_color($user->status),UserStatus::get_name($user->status));
        })

        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request)
  {
    $filters = [
      'user_type_filter' => 'type',
      'user_status_filter' => 'status',
    ];

    $query =User::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    if ($request->has('user_role_filter') && $request->user_role_filter != '') {
      $query->role($request->user_role_filter);
    }

    return $query->get();
  }

}
