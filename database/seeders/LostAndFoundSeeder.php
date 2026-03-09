<?php

namespace Database\Seeders;

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

        // Lost item 1 - Found
        $passenger1 = $passengers->where('first_name', 'Ahmed')->first();
        if ($passenger1) {
            LostAndFound::create([
                'user_id' => $passenger1->user_id,
                'description' => 'Black leather wallet containing ID card and credit cards. Lost in taxi ride from airport.',
                'status' => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 2 - Returned
        $passenger2 = $passengers->where('first_name', 'Fatima')->first();
        if ($passenger2) {
            LostAndFound::create([
                'user_id' => $passenger2->user_id,
                'description' => 'Blue backpack with laptop and documents. Left in the cargo transport vehicle.',
                'status' => LostAndFoundStatus::RETURNED,
            ]);
        }

        // Lost item 3 - Found
        $passenger3 = $passengers->where('first_name', 'Mohammed')->first();
        if ($passenger3) {
            LostAndFound::create([
                'user_id' => $passenger3->user_id,
                'description' => 'Samsung Galaxy S21 smartphone with black case. Lost during international trip.',
                'status' => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 4 - Found
        if ($passenger1) {
            LostAndFound::create([
                'user_id' => $passenger1->user_id,
                'description' => 'Brown leather jacket, size M. Left in the car rescue vehicle.',
                'status' => LostAndFoundStatus::FOUND,
            ]);
        }

        // Lost item 5 - Returned
        if ($passenger2) {
            LostAndFound::create([
                'user_id' => $passenger2->user_id,
                'description' => 'Silver watch with black leather strap. Found under passenger seat.',
                'status' => LostAndFoundStatus::RETURNED,
            ]);
        }
    }
}
