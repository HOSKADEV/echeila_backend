<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\LostAndFound;
use App\Models\Passenger;
use App\Constants\LostAndFoundStatus;
use Illuminate\Database\Seeder;

class LostAndFoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passengers = Passenger::all();
        $drivers    = Driver::all();

        // Lost item 1 - Found (passenger)
        $passenger1 = $passengers->where('first_name', 'Ahmed')->first();
        if ($passenger1) {
            LostAndFound::create([
                'finder_type' => Passenger::class,
                'finder_id'   => $passenger1->id,
                'description' => 'Black leather wallet containing ID card and credit cards. Lost in taxi ride from airport.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 2 - Returned (passenger)
        $passenger2 = $passengers->where('first_name', 'Fatima')->first();
        if ($passenger2) {
            LostAndFound::create([
                'finder_type' => Passenger::class,
                'finder_id'   => $passenger2->id,
                'description' => 'Blue backpack with laptop and documents. Left in the cargo transport vehicle.',
                'status'      => LostAndFoundStatus::RETURNED,
            ]);
        }

        // Lost item 3 - Found (passenger)
        $passenger3 = $passengers->where('first_name', 'Mohammed')->first();
        if ($passenger3) {
            LostAndFound::create([
                'finder_type' => Passenger::class,
                'finder_id'   => $passenger3->id,
                'description' => 'Samsung Galaxy S21 smartphone with black case. Lost during international trip.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 4 - Found (passenger)
        if ($passenger1) {
            LostAndFound::create([
                'finder_type' => Passenger::class,
                'finder_id'   => $passenger1->id,
                'description' => 'Brown leather jacket, size M. Left in the car rescue vehicle.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 5 - Returned (passenger)
        if ($passenger2) {
            LostAndFound::create([
                'finder_type' => Passenger::class,
                'finder_id'   => $passenger2->id,
                'description' => 'Silver watch with black leather strap. Found under passenger seat.',
                'status'      => LostAndFoundStatus::RETURNED,
            ]);
        }

        // Lost item 6 - Found (driver: Karim)
        $driver1 = $drivers->where('first_name', 'Karim')->first();
        if ($driver1) {
            LostAndFound::create([
                'finder_type' => Driver::class,
                'finder_id'   => $driver1->id,
                'description' => 'Red umbrella with wooden handle. Found behind the back seat after a taxi trip.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 7 - Returned (driver: Rachid)
        $driver2 = $drivers->where('first_name', 'Rachid')->first();
        if ($driver2) {
            LostAndFound::create([
                'finder_type' => Driver::class,
                'finder_id'   => $driver2->id,
                'description' => 'Pair of prescription glasses in a hard case. Left on the passenger seat.',
                'status'      => LostAndFoundStatus::RETURNED,
            ]);
        }

        // Lost item 8 - Found (driver: Youcef)
        $driver3 = $drivers->where('first_name', 'Youcef')->first();
        if ($driver3) {
            LostAndFound::create([
                'finder_type' => Driver::class,
                'finder_id'   => $driver3->id,
                'description' => 'Children\'s toy set in a pink bag. Forgotten in the vehicle after a cargo transport trip.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 9 - Found (driver: Said)
        $driver4 = $drivers->where('first_name', 'Said')->first();
        if ($driver4) {
            LostAndFound::create([
                'finder_type' => Driver::class,
                'finder_id'   => $driver4->id,
                'description' => 'Work laptop bag containing charger and notebook. Left in the trunk after an international trip.',
                'status'      => LostAndFoundStatus::FOUND,
            ]);
        }
    }
}
