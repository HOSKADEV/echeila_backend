<?php

namespace App\Http\Controllers\Dashboard\Permissions;

use App\Http\Controllers\Controller;
use App\Support\Enum\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
  public function index()
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_PERMISSIONS)) {
      return redirect()->route('unauthorized');
    }

    $roles = Role::with('permissions')->get();
    $permissions = Permission::with('roles')->get();
    
    // Get permission groups from Permissions enum
    $permissionGroups = Permissions::getPermissionGroups();
    
    // Group permissions by category
    $groupedPermissions = [];
    foreach ($permissions as $permission) {
      foreach ($permissionGroups as $groupKey => $groupPermissions) {
        if (in_array($permission->name, $groupPermissions)) {
          $groupedPermissions[$groupKey][] = $permission;
          break;
        }
      }
    }
    
    return view('dashboard.permission.list', [
      'roles' => $roles,
      'permissions' => $permissions,
      'groupedPermissions' => $groupedPermissions
    ]);
  }

  /**
   * @throws \Throwable
   */
  public function update(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(Permissions::MANAGE_PERMISSIONS)) {
      return response()->json(['message' => __('app.unauthorized')], 403);
    }
    // Validate the request
    $validated = $request->validate([
      'roles' => 'sometimes|array', // Roles should be an array
      'roles.*.id' => 'required|integer|exists:roles,id', // Each role should have a valid ID
      'roles.*.permissions' => 'required|array', // Each role should have an array of permissions
      'roles.*.permissions.*' => 'integer|exists:permissions,id', // Each permission should be an integer and exist in the permissions table
    ]);

    // Collect all permission IDs into a flat array
    $permissionIds = [];
    foreach ($validated['roles'] as $role) {
      $permissionIds = array_merge($permissionIds, $role['permissions']);
    }

    // Retrieve all permissions based on the validated IDs
    $permissions = Permission::whereIn('id', $permissionIds)->get()->keyBy('id');

    DB::beginTransaction();
    // Remove all permissions from all roles
    Role::all()->each(function ($role) {
      $role->syncPermissions([]);
    });
    // Iterate through each role and sync permissions
    foreach ($validated['roles'] as $role) {
      $roleId = $role['id'];
      $permissionIds = $role['permissions'];

      // Map permission IDs to their names
      $Permissions = $permissions->only($permissionIds)->pluck('name')->toArray();

      // Find the role by ID and sync the permissions
      $foundRole = Role::find($roleId);
      if ($foundRole) {
        $foundRole->syncPermissions($Permissions); // Update permissions with names
      }
    }
    DB::commit();

    // Return a success response
    return response()->json(['success' => true]);
  }
}
