<?php

namespace App\Http\Controllers\Dashboard\Roles;

use App\Datatables\RoleDatatable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Support\Enum\Permissions;
use App\Support\Enum\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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

  public function create()
  {
    if (!$this->isSuperAdmin()) {
      return redirect()->route('unauthorized');
    }

    return view('dashboard.role.create');
  }

  public function store(Request $request)
  {
    if (!$this->isSuperAdmin()) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'name' => 'required|string|max:255|unique:roles,name',
      'guard_name' => 'required|string|max:255',
    ]);

    try {
      DB::beginTransaction();
      Role::create($data);
      DB::commit();

      return redirect()->route('roles.index')->with('success', __('app.created_successfully', ['name' => __('app.role')]));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
  }

  public function edit($id)
  {
    if (!$this->isSuperAdmin()) {
      return redirect()->route('unauthorized');
    }

    return view('dashboard.role.edit')->with([
      'role' => Role::findOrFail($id),
    ]);
  }

  public function update(Request $request, $id)
  {
    if (!$this->isSuperAdmin()) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'name' => 'required|string|max:255|unique:roles,name,' . $id,
      'guard_name' => 'required|string|max:255',
    ]);

    try {
      DB::beginTransaction();
      $role = Role::findOrFail($id);
      $role->update($data);
      DB::commit();

      return redirect()->route('roles.index')->with('success', __('app.updated_successfully', ['name' => __('app.role')]));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!$this->isSuperAdmin()) {
      return redirect()->route('unauthorized');
    }

    try {
      $role = Role::findOrFail($id);

      if (Admin::role($role->name)->exists()) {
        return redirect()->route('roles.index')->with('error', __('app.role_delete_has_admins'));
      }

      if (in_array($role->name, [Roles::SUPER_ADMIN, Roles::ADMIN], true)) {
        return redirect()->route('roles.index')->with('error', __('app.role_delete_protected'));
      }

      $role->delete();

      return redirect()->route('roles.index')->with('success', __('app.deleted_successfully', ['name' => __('app.role')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  private function isSuperAdmin(): bool
  {
    return auth()->check() && auth()->user()->hasRole(Roles::SUPER_ADMIN);
  }
}
