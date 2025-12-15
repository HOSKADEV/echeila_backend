<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get brands by their English names for reference
        $toyota = Brand::whereJsonContains('name->en', 'Toyota')->first();
        $mercedes = Brand::whereJsonContains('name->en', 'Mercedes-Benz')->first();
        $volkswagen = Brand::whereJsonContains('name->en', 'Volkswagen')->first();
        $ford = Brand::whereJsonContains('name->en', 'Ford')->first();
        $bmw = Brand::whereJsonContains('name->en', 'BMW')->first();
        $audi = Brand::whereJsonContains('name->en', 'Audi')->first();
        $hyundai = Brand::whereJsonContains('name->en', 'Hyundai')->first();
        $kia = Brand::whereJsonContains('name->en', 'Kia')->first();

        $models = [
            // Toyota Models
            [
                'brand_id' => $toyota?->id,
                'name' => [
                    'en' => 'Corolla',
                    'ar' => 'كورولا',
                    'fr' => 'Corolla'
                ],
            ],
            [
                'brand_id' => $toyota?->id,
                'name' => [
                    'en' => 'Camry',
                    'ar' => 'كامري',
                    'fr' => 'Camry'
                ],
            ],
            [
                'brand_id' => $toyota?->id,
                'name' => [
                    'en' => 'Prius',
                    'ar' => 'بريوس',
                    'fr' => 'Prius'
                ],
            ],
            // Mercedes Models
            [
                'brand_id' => $mercedes?->id,
                'name' => [
                    'en' => 'C-Class',
                    'ar' => 'الفئة سي',
                    'fr' => 'Classe C'
                ],
            ],
            [
                'brand_id' => $mercedes?->id,
                'name' => [
                    'en' => 'E-Class',
                    'ar' => 'الفئة إي',
                    'fr' => 'Classe E'
                ],
            ],
            // Volkswagen Models
            [
                'brand_id' => $volkswagen?->id,
                'name' => [
                    'en' => 'Golf',
                    'ar' => 'جولف',
                    'fr' => 'Golf'
                ],
            ],
            [
                'brand_id' => $volkswagen?->id,
                'name' => [
                    'en' => 'Passat',
                    'ar' => 'باسات',
                    'fr' => 'Passat'
                ],
            ],
            // Ford Models
            [
                'brand_id' => $ford?->id,
                'name' => [
                    'en' => 'Focus',
                    'ar' => 'فوكس',
                    'fr' => 'Focus'
                ],
            ],
            [
                'brand_id' => $ford?->id,
                'name' => [
                    'en' => 'Mustang',
                    'ar' => 'موستانج',
                    'fr' => 'Mustang'
                ],
            ],
            // BMW Models
            [
                'brand_id' => $bmw?->id,
                'name' => [
                    'en' => '3 Series',
                    'ar' => 'السلسلة 3',
                    'fr' => 'Série 3'
                ],
            ],
            [
                'brand_id' => $bmw?->id,
                'name' => [
                    'en' => '5 Series',
                    'ar' => 'السلسلة 5',
                    'fr' => 'Série 5'
                ],
            ],
            // Audi Models
            [
                'brand_id' => $audi?->id,
                'name' => [
                    'en' => 'A4',
                    'ar' => 'إيه 4',
                    'fr' => 'A4'
                ],
            ],
            [
                'brand_id' => $audi?->id,
                'name' => [
                    'en' => 'A6',
                    'ar' => 'إيه 6',
                    'fr' => 'A6'
                ],
            ],
            // Hyundai Models
            [
                'brand_id' => $hyundai?->id,
                'name' => [
                    'en' => 'Elantra',
                    'ar' => 'إلانترا',
                    'fr' => 'Elantra'
                ],
            ],
            [
                'brand_id' => $hyundai?->id,
                'name' => [
                    'en' => 'Sonata',
                    'ar' => 'سوناتا',
                    'fr' => 'Sonata'
                ],
            ],
            // Kia Models
            [
                'brand_id' => $kia?->id,
                'name' => [
                    'en' => 'Optima',
                    'ar' => 'أوبتيما',
                    'fr' => 'Optima'
                ],
            ],
            [
                'brand_id' => $kia?->id,
                'name' => [
                    'en' => 'Sportage',
                    'ar' => 'سبورتاج',
                    'fr' => 'Sportage'
                ],
            ],
        ];

        foreach ($models as $model) {
            if ($model['brand_id']) {
                VehicleModel::create($model);
            }
        }
    }
}