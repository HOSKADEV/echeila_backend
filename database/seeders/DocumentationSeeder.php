<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constants\DocumentationKey;
use App\Models\Documentation;

class DocumentationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    foreach (DocumentationKey::all() as $key) {
      Documentation::firstOrCreate(['key' => $key]);
    }
  }
}
