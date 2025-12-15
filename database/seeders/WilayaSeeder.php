<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $wilayas = [
      [
        'name' => [
          'en' => 'Algiers',
          'ar' => 'الجزائر',
          'fr' => 'Alger'
        ],
        'latitude' => 36.7538,
        'longitude' => 3.0588,
      ],
      [
        'name' => [
          'en' => 'Oran',
          'ar' => 'وهران',
          'fr' => 'Oran'
        ],
        'latitude' => 35.6969,
        'longitude' => -0.6331,
      ],
      [
        'name' => [
          'en' => 'Annaba',
          'ar' => 'عنابة',
          'fr' => 'Annaba'
        ],
        'latitude' => 36.3650,
        'longitude' => 6.6147,
      ],
      [
        'name' => [
          'en' => 'Skikda',
          'ar' => 'سكيكدة',
          'fr' => 'Skikda'
        ],
        'latitude' => 36.9000,
        'longitude' => 7.7667,
      ],
      [
        'name' => [
          'en' => 'Setif',
          'ar' => 'سطيف',
          'fr' => 'Sétif'
        ],
        'latitude' => 36.1833,
        'longitude' => 5.4167,
      ],
    ];

    foreach ($wilayas as $wilaya) {
      Wilaya::create($wilaya);
    }
  }
}