<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            [
                'name' => [
                    'en' => 'White',
                    'ar' => 'أبيض',
                    'fr' => 'Blanc'
                ],
                'code' => '#FFFFFF',
            ],
            [
                'name' => [
                    'en' => 'Black',
                    'ar' => 'أسود',
                    'fr' => 'Noir'
                ],
                'code' => '#000000',
            ],
            [
                'name' => [
                    'en' => 'Red',
                    'ar' => 'أحمر',
                    'fr' => 'Rouge'
                ],
                'code' => '#FF0000',
            ],
            [
                'name' => [
                    'en' => 'Blue',
                    'ar' => 'أزرق',
                    'fr' => 'Bleu'
                ],
                'code' => '#0000FF',
            ],
            [
                'name' => [
                    'en' => 'Silver',
                    'ar' => 'فضي',
                    'fr' => 'Argent'
                ],
                'code' => '#C0C0C0',
            ],
            [
                'name' => [
                    'en' => 'Gray',
                    'ar' => 'رمادي',
                    'fr' => 'Gris'
                ],
                'code' => '#808080',
            ],
            [
                'name' => [
                    'en' => 'Green',
                    'ar' => 'أخضر',
                    'fr' => 'Vert'
                ],
                'code' => '#008000',
            ],
            [
                'name' => [
                    'en' => 'Yellow',
                    'ar' => 'أصفر',
                    'fr' => 'Jaune'
                ],
                'code' => '#FFFF00',
            ],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}