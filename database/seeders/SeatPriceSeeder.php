<?php

namespace Database\Seeders;

use App\Models\SeatPrice;
use Illuminate\Database\Seeder;

class SeatPriceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seatPrices = [
      // From Wilaya A (ID: 1)
      ['starting_wilaya_id' => 1, 'arrival_wilaya_id' => 2, 'default_seat_price' => 1500.00],
      ['starting_wilaya_id' => 1, 'arrival_wilaya_id' => 3, 'default_seat_price' => 2000.00],
      ['starting_wilaya_id' => 1, 'arrival_wilaya_id' => 4, 'default_seat_price' => 2500.00],
      ['starting_wilaya_id' => 1, 'arrival_wilaya_id' => 5, 'default_seat_price' => 1800.00],
      
      // From Wilaya B (ID: 2)
      ['starting_wilaya_id' => 2, 'arrival_wilaya_id' => 1, 'default_seat_price' => 1500.00],
      ['starting_wilaya_id' => 2, 'arrival_wilaya_id' => 3, 'default_seat_price' => 2200.00],
      ['starting_wilaya_id' => 2, 'arrival_wilaya_id' => 4, 'default_seat_price' => 2800.00],
      ['starting_wilaya_id' => 2, 'arrival_wilaya_id' => 5, 'default_seat_price' => 1900.00],
      
      // From Wilaya C (ID: 3)
      ['starting_wilaya_id' => 3, 'arrival_wilaya_id' => 1, 'default_seat_price' => 2000.00],
      ['starting_wilaya_id' => 3, 'arrival_wilaya_id' => 2, 'default_seat_price' => 2200.00],
      ['starting_wilaya_id' => 3, 'arrival_wilaya_id' => 4, 'default_seat_price' => 1200.00],
      ['starting_wilaya_id' => 3, 'arrival_wilaya_id' => 5, 'default_seat_price' => 800.00],
      
      // From Wilaya D (ID: 4)
      ['starting_wilaya_id' => 4, 'arrival_wilaya_id' => 1, 'default_seat_price' => 2500.00],
      ['starting_wilaya_id' => 4, 'arrival_wilaya_id' => 2, 'default_seat_price' => 2800.00],
      ['starting_wilaya_id' => 4, 'arrival_wilaya_id' => 3, 'default_seat_price' => 1200.00],
      ['starting_wilaya_id' => 4, 'arrival_wilaya_id' => 5, 'default_seat_price' => 1600.00],
      
      // From Wilaya E (ID: 5)
      ['starting_wilaya_id' => 5, 'arrival_wilaya_id' => 1, 'default_seat_price' => 1800.00],
      ['starting_wilaya_id' => 5, 'arrival_wilaya_id' => 2, 'default_seat_price' => 1900.00],
      ['starting_wilaya_id' => 5, 'arrival_wilaya_id' => 3, 'default_seat_price' => 800.00],
      ['starting_wilaya_id' => 5, 'arrival_wilaya_id' => 4, 'default_seat_price' => 1600.00],
    ];

    foreach ($seatPrices as $seatPrice) {
      SeatPrice::create($seatPrice);
    }
  }
}