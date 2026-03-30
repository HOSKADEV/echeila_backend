<?php

namespace App\Datatables;

use App\Support\Enum\Roles;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Log;
use Spatie\Permission\Models\Role;


class RoleDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "name",
      "guard_name",
      "actions",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("actions", function (Role $role) {
          $isSuperAdmin = Auth::user()->hasRole(Roles::SUPER_ADMIN);
          $isDeleteable = !in_array($role->name, [Roles::SUPER_ADMIN, Roles::ADMIN]);

          return $this
            ->edit(route("roles.edit", $role->id), $isSuperAdmin && $isDeleteable)
            ->delete($role->id, $isSuperAdmin && $isDeleteable)
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (Role $role) {
          //test locale ar
          return app()->getLocale() == 'ar' ? $this->bold(Roles::get_name($role->name)) : $this->bold($role->name);
        })
        ->addColumn("guard_name", function (Role $role) {
          return $role->guard_name;
        })
        ->rawColumns(self::columns())

        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request)
  {
    $query =Role::query();
    return $query->get();
  }

}
