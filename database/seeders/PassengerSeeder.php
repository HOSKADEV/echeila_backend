<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Passenger;
use App\Models\Wallet;
use App\Constants\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create passenger 1
        $user1 = User::create([
            'username' => 'ECH-25-A1B2C3',
            'phone' => '0555111111',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        Passenger::create([
            'user_id' => $user1->id,
            'first_name' => 'Ahmed',
            'last_name' => 'Benali',
            'birth_date' => '1990-05-15',
        ]);

        Wallet::create([
            'user_id' => $user1->id,
            'balance' => 5000.00,
        ]);

        // Create passenger 2
        $user2 = User::create([
            'username' => 'ECH-25-D4E5F6',
            'phone' => '0555222222',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        Passenger::create([
            'user_id' => $user2->id,
            'first_name' => 'Fatima',
            'last_name' => 'Zahra',
            'birth_date' => '1992-08-20',
        ]);

        Wallet::create([
            'user_id' => $user2->id,
            'balance' => 3000.00,
        ]);

        // Create passenger 3
        $user3 = User::create([
            'username' => 'ECH-25-G7H8I9',
            'phone' => '0555333333',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        Passenger::create([
            'user_id' => $user3->id,
            'first_name' => 'Mohammed',
            'last_name' => 'Khalil',
            'birth_date' => '1988-12-10',
        ]);

        Wallet::create([
            'user_id' => $user3->id,
            'balance' => 7500.00,
        ]);
    }
}
