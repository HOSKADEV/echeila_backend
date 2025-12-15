<?php
namespace Database\Seeders;


use App\Support\Enum\Permissions;
use App\Support\Enum\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    foreach (Permissions::lists() as $permission) {
      Permission::findOrCreate($permission);
    }

    //assign super admin permissions
    $superAdminPermissions = Permissions::lists();

    Permission::whereIn('name', $superAdminPermissions)
      ->each(function ($permission) {
        $permission->assignRole([Roles::SUPER_ADMIN]);
      });

    //assign admin permissions
    $adminPermissions = [
    ];

    Permission::whereIn('name', $adminPermissions)
      ->each(function ($permission) {
        $permission->assignRole([Roles::ADMIN]);
      });
  }
}
