<?php

namespace App\Http\Controllers\Dashboard\Roles;

use App\Datatables\RoleDatatable;
use App\Http\Controllers\Controller;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;

class RolesController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_ROLES)) {
      return redirect()->route('unauthorized');
    }

    $tenants = new RoleDatatable();
    if ($request->wantsJson()) {
      return $tenants->datatables($request);
    }
    return view("dashboard.role.list")->with([
      "columns" => $tenants::columns(),
    ]);
  }
}
