<?php
namespace Database\Seeders;

use App\Support\Enum\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Role::create(['name' => Roles::SUPER_ADMIN]);
    Role::create(['name' => Roles::ADMIN]);
  }
}
