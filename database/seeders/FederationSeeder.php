<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Federation;
use App\Models\Wallet;
use App\Constants\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FederationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create federation 1
        $user1 = User::create([
            'username' => 'ECH-25-FED1A1',
            'phone' => '0777111111',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        Federation::create([
            'user_id' => $user1->id,
            'name' => 'Federation des Transporteurs du Nord',
            'description' => 'Federation responsible for coordinating transportation services in the northern regions of Algeria.',
            'creation_date' => now()->subYears(5),
        ]);

        Wallet::create([
            'user_id' => $user1->id,
            'balance' => 50000.00,
        ]);

        // Create federation 2
        $user2 = User::create([
            'username' => 'ECH-25-FED2B2',
            'phone' => '0777222222',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        Federation::create([
            'user_id' => $user2->id,
            'name' => 'Federation des Chauffeurs de Taxi',
            'description' => 'Professional federation for taxi drivers across major cities.',
            'creation_date' => now()->subYears(3),
        ]);

        Wallet::create([
            'user_id' => $user2->id,
            'balance' => 35000.00,
        ]);
    }
}
