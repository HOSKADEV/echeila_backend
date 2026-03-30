<?php

namespace App\Http\Controllers\Dashboard\Permissions;

use App\Http\Controllers\Controller;
use App\Support\Enum\Permissions;
use App\Support\Enum\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
  public function index()
  {
    if (!$this->canManagePermissions()) {
      return redirect()->route('unauthorized');
    }

    $isSuperAdmin = $this->isSuperAdmin();
    $roles = Role::with('permissions')
      ->when(!$isSuperAdmin, function ($query) {
        $query->whereNotIn('name', [Roles::SUPER_ADMIN, Roles::ADMIN]);
      })
      ->get();

    $permissionGroups = Permissions::getPermissionGroups();
    $excludedPermissions = $this->excludedPermissionNamesForAdmin();
    $customRoleBlockedPermissions = $this->blockedPermissionNamesForCustomRoles();
    $userPermissionNames = auth()->user()->getAllPermissions()->pluck('name')->all();

    $permissions = Permission::with('roles')->get();

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
      'isSuperAdmin' => $isSuperAdmin,
      'protectedRoleNames' => [Roles::SUPER_ADMIN, Roles::ADMIN],
      'excludedPermissionNames' => $excludedPermissions,
      'customRoleBlockedPermissionNames' => $customRoleBlockedPermissions,
      'userPermissionNames' => $userPermissionNames,
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
    if (!$this->canManagePermissions()) {
      return response()->json(['message' => __('app.unauthorized')], 403);
    }

    $isSuperAdmin = $this->isSuperAdmin();

    $validated = $request->validate([
      'roles' => 'required|array',
      'roles.*.id' => 'required|integer|exists:roles,id',
      'roles.*.permissions' => 'required|array',
      'roles.*.permissions.*' => 'integer|exists:permissions,id',
    ]);

    $payloadRoles = collect($validated['roles']);
    $roleIds = $payloadRoles->pluck('id')->map(fn($id) => (int) $id)->unique()->values()->all();

    $roles = Role::whereIn('id', $roleIds)->get()->keyBy('id');
    if ($roles->count() !== count($roleIds)) {
      return response()->json(['message' => __('app.invalid_role_selection')], 422);
    }

    if (!$isSuperAdmin) {
      $hasProtectedRole = $roles->contains(function (Role $role) {
        return in_array($role->name, [Roles::SUPER_ADMIN, Roles::ADMIN], true);
      });

      if ($hasProtectedRole) {
        return response()->json(['message' => __('app.permission_scope_forbidden_role')], 403);
      }
    }

    $permissionIds = $payloadRoles
      ->pluck('permissions')
      ->flatten()
      ->map(fn($id) => (int) $id)
      ->unique()
      ->values()
      ->all();

    $permissions = Permission::whereIn('id', $permissionIds)->get()->keyBy('id');
    $customRoleBlockedPermissionNames = $this->blockedPermissionNamesForCustomRoles();
    $customRoleBlockedPermissionIds = Permission::whereIn('name', $customRoleBlockedPermissionNames)->pluck('id')->map(fn($id) => (int) $id)->all();

    foreach ($validated['roles'] as $roleData) {
      $roleId = (int) $roleData['id'];
      $selectedPermissionIds = collect($roleData['permissions'])->map(fn($id) => (int) $id)->all();
      $role = $roles[$roleId];
      $isCustomRole = !in_array($role->name, [Roles::SUPER_ADMIN, Roles::ADMIN], true);

      if ($isCustomRole && !empty(array_intersect($selectedPermissionIds, $customRoleBlockedPermissionIds))) {
        return response()->json(['message' => __('app.permission_scope_forbidden_permission')], 403);
      }
    }

    if (!$isSuperAdmin) {
      $excludedPermissionNames = $this->excludedPermissionNamesForAdmin();
      $blockedPermissionIds = Permission::whereIn('name', $excludedPermissionNames)->pluck('id')->all();
      $userPermissionIds = auth()->user()->getAllPermissions()->pluck('id')->map(fn($id) => (int) $id)->all();

      $intersectedBlockedPermissions = array_intersect($permissionIds, $blockedPermissionIds);
      if (!empty($intersectedBlockedPermissions)) {
        return response()->json(['message' => __('app.permission_scope_forbidden_permission')], 403);
      }

      $notOwnedPermissionIds = array_diff($permissionIds, $userPermissionIds);
      if (!empty($notOwnedPermissionIds)) {
        return response()->json(['message' => __('app.permission_scope_forbidden_permission')], 403);
      }
    }

    try {
      DB::beginTransaction();

      $editablePermissionNames = null;
      if (!$isSuperAdmin) {
        $editablePermissionNames = auth()->user()->getAllPermissions()
          ->pluck('name')
          ->diff($this->excludedPermissionNamesForAdmin())
          ->values();
      }

      foreach ($validated['roles'] as $roleData) {
        $roleId = (int) $roleData['id'];
        $selectedPermissionIds = collect($roleData['permissions'])->map(fn($id) => (int) $id)->all();
        $role = $roles[$roleId];

        $selectedPermissionNames = $permissions->only($selectedPermissionIds)->pluck('name');

        // Preserve currently locked permissions even if they are missing from payload.
        $lockedPermissionNames = $this->lockedPermissionNamesForRole(
          $role,
          $isSuperAdmin,
          $customRoleBlockedPermissionNames,
          $editablePermissionNames
        );

        $selectedPermissionNames = $selectedPermissionNames->merge($lockedPermissionNames)->unique()->values();

        $roles[$roleId]->syncPermissions($selectedPermissionNames);
      }

      DB::commit();

      return response()->json(['success' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  private function canManagePermissions(): bool
  {
    if (!auth()->check()) {
      return false;
    }

    return $this->isSuperAdmin() || auth()->user()->hasPermissionTo(Permissions::MANAGE_PERMISSIONS);
  }

  private function isSuperAdmin(): bool
  {
    return auth()->check() && auth()->user()->hasRole(Roles::SUPER_ADMIN);
  }

  private function excludedPermissionNamesForAdmin(): array
  {
    $permissionGroups = Permissions::getPermissionGroups();
    $excludedGroups = ['system_management', 'admin_management', 'zone_management'];
    $excludedPermissionNames = [];

    foreach ($excludedGroups as $group) {
      if (!isset($permissionGroups[$group])) {
        continue;
      }

      $excludedPermissionNames = array_merge($excludedPermissionNames, $permissionGroups[$group]);
    }

    return array_values(array_unique($excludedPermissionNames));
  }

  private function blockedPermissionNamesForCustomRoles(): array
  {
    $permissionGroups = Permissions::getPermissionGroups();
    $blockedGroups = ['system_management', 'admin_management'];
    $blockedPermissionNames = [];

    foreach ($blockedGroups as $group) {
      if (!isset($permissionGroups[$group])) {
        continue;
      }

      $blockedPermissionNames = array_merge($blockedPermissionNames, $permissionGroups[$group]);
    }

    return array_values(array_unique($blockedPermissionNames));
  }

  private function lockedPermissionNamesForRole(
    Role $role,
    bool $isSuperAdmin,
    array $customRoleBlockedPermissionNames,
    $editablePermissionNames
  ) {
    $currentRolePermissionNames = $role->permissions->pluck('name');
    $lockedPermissionNames = collect();

    if ($role->name === Roles::SUPER_ADMIN) {
      $superAdminCriticalPermissions = [
        Permissions::MANAGE_ROLES,
        Permissions::MANAGE_PERMISSIONS,
      ];

      $lockedPermissionNames = $lockedPermissionNames->merge(
        $currentRolePermissionNames->filter(fn($name) => in_array($name, $superAdminCriticalPermissions, true))
      );
    }

    $isCustomRole = !in_array($role->name, [Roles::SUPER_ADMIN, Roles::ADMIN], true);
    if ($isCustomRole) {
      $lockedPermissionNames = $lockedPermissionNames->merge(
        $currentRolePermissionNames->filter(fn($name) => in_array($name, $customRoleBlockedPermissionNames, true))
      );
    }

    if (!$isSuperAdmin && $editablePermissionNames !== null) {
      $lockedPermissionNames = $lockedPermissionNames->merge(
        $currentRolePermissionNames->reject(fn($name) => $editablePermissionNames->contains($name))
      );
    }

    return $lockedPermissionNames->unique()->values();
  }
}
