<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name' => [
                    'en' => 'Toyota',
                    'ar' => 'تويوتا',
                    'fr' => 'Toyota'
                ],
            ],
            [
                'name' => [
                    'en' => 'Mercedes-Benz',
                    'ar' => 'مرسيدس بنز',
                    'fr' => 'Mercedes-Benz'
                ],
            ],
            [
                'name' => [
                    'en' => 'Volkswagen',
                    'ar' => 'فولكس فاجن',
                    'fr' => 'Volkswagen'
                ],
            ],
            [
                'name' => [
                    'en' => 'Ford',
                    'ar' => 'فورد',
                    'fr' => 'Ford'
                ],
            ],
            [
                'name' => [
                    'en' => 'BMW',
                    'ar' => 'بي إم دبليو',
                    'fr' => 'BMW'
                ],
            ],
            [
                'name' => [
                    'en' => 'Audi',
                    'ar' => 'أودي',
                    'fr' => 'Audi'
                ],
            ],
            [
                'name' => [
                    'en' => 'Hyundai',
                    'ar' => 'هيونداي',
                    'fr' => 'Hyundai'
                ],
            ],
            [
                'name' => [
                    'en' => 'Kia',
                    'ar' => 'كيا',
                    'fr' => 'Kia'
                ],
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}