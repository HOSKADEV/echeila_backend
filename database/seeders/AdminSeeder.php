<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Support\Enum\Roles;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = Admin::create([
      'firstname' => 'Super',
      'lastname' => 'Admin',
      'email' => 'super@admin.com',
      'phone' => '0666666666',
      'password' => bcrypt('123456789'),
    ]);
    // public/storage/uploads/users/avatars/1.png
//      $filePath = public_path('assets/img/avatars/1.png');
//      \Storage::disk('public')->putFileAs('uploads/users/avatars', $filePath, $user->id .'.png');
//      $user->avatar = 'uploads/users/avatars/' . $user->id . '.png';
//      $user->save();
    $user->assignRole(Roles::SUPER_ADMIN);
  }
}
