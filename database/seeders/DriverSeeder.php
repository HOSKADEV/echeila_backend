<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Card;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\Federation;
use App\Models\VehicleModel;
use App\Models\Color;
use App\Constants\UserStatus;
use App\Constants\DriverStatus;
use App\Constants\CardType;
use App\Constants\TripType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $federation1 = Federation::first();
        $federation2 = Federation::skip(1)->first();
        $vehicleModel = VehicleModel::first();
        $color = Color::first();

        // Create driver 1 - Taxi service
        $user1 = User::create([
            'username' => 'ECH-25-DRV1K1',
            'phone' => '+213666111111',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        $driver1 = Driver::create([
            'user_id' => $user1->id,
            'federation_id' => $federation1?->id,
            'first_name' => 'Karim',
            'last_name' => 'Messaoudi',
            'birth_date' => '1985-03-15',
            'email' => 'karim.messaoudi@example.com',
            'status' => DriverStatus::APPROVED,
        ]);

        Wallet::create([
            'user_id' => $user1->id,
            'balance' => 12000.00,
        ]);

        // Vehicle for driver 1
        if ($vehicleModel && $color) {
            Vehicle::create([
                'driver_id' => $driver1->id,
                'model_id' => $vehicleModel->id,
                'color_id' => $color->id,
                'production_year' => 2018,
                'plate_number' => '16-123456-20',
            ]);
        }

        // Cards for driver 1
        Card::create([
            'driver_id' => $driver1->id,
            'type' => CardType::NATIONAL_ID,
            'number' => '199012345678901',
            'expiration_date' => now()->addYears(5),
        ]);

        Card::create([
            'driver_id' => $driver1->id,
            'type' => CardType::DRIVING_LICENSE,
            'number' => 'DL123456789',
            'expiration_date' => now()->addYears(3),
        ]);

        // Services for driver 1
        Service::create([
            'driver_id' => $driver1->id,
            'trip_type' => TripType::TAXI_RIDE,
        ]);

        Service::create([
            'driver_id' => $driver1->id,
            'trip_type' => TripType::PAID_DRIVING,
        ]);

        // Create driver 2 - Cargo transport service
        $user2 = User::create([
            'username' => 'ECH-25-DRV2M2',
            'phone' => '+213666222222',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        $driver2 = Driver::create([
            'user_id' => $user2->id,
            'federation_id' => $federation1?->id,
            'first_name' => 'Rachid',
            'last_name' => 'Benmohamed',
            'birth_date' => '1982-07-22',
            'email' => 'rachid.benmohamed@example.com',
            'status' => DriverStatus::APPROVED,
        ]);

        Wallet::create([
            'user_id' => $user2->id,
            'balance' => 8500.00,
        ]);

        // Vehicle for driver 2
        if ($vehicleModel && $color) {
            Vehicle::create([
                'driver_id' => $driver2->id,
                'model_id' => $vehicleModel->id,
                'color_id' => $color->id,
                'production_year' => 2016,
                'plate_number' => '16-234567-20',
            ]);
        }

        // Cards for driver 2
        Card::create([
            'driver_id' => $driver2->id,
            'type' => CardType::NATIONAL_ID,
            'number' => '198212345678902',
            'expiration_date' => now()->addYears(5),
        ]);

        Card::create([
            'driver_id' => $driver2->id,
            'type' => CardType::DRIVING_LICENSE,
            'number' => 'DL987654321',
            'expiration_date' => now()->addYears(3),
        ]);

        // Services for driver 2
        Service::create([
            'driver_id' => $driver2->id,
            'trip_type' => TripType::CARGO_TRANSPORT,
        ]);

        // Create driver 3 - International trips (MRT/ESP)
        $user3 = User::create([
            'username' => 'ECH-25-DRV3N3',
            'phone' => '+213666333333',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        $driver3 = Driver::create([
            'user_id' => $user3->id,
            'federation_id' => $federation2?->id,
            'first_name' => 'Youcef',
            'last_name' => 'Hamidi',
            'birth_date' => '1980-11-30',
            'email' => 'youcef.hamidi@example.com',
            'status' => DriverStatus::APPROVED,
        ]);

        Wallet::create([
            'user_id' => $user3->id,
            'balance' => 15000.00,
        ]);

        // Vehicle for driver 3
        if ($vehicleModel && $color) {
            Vehicle::create([
                'driver_id' => $driver3->id,
                'model_id' => $vehicleModel->id,
                'color_id' => $color->id,
                'production_year' => 2019,
                'plate_number' => '16-345678-20',
            ]);
        }

        // Cards for driver 3
        Card::create([
            'driver_id' => $driver3->id,
            'type' => CardType::NATIONAL_ID,
            'number' => '198012345678903',
            'expiration_date' => now()->addYears(5),
        ]);

        Card::create([
            'driver_id' => $driver3->id,
            'type' => CardType::DRIVING_LICENSE,
            'number' => 'DL555666777',
            'expiration_date' => now()->addYears(3),
        ]);

        // Services for driver 3
        Service::create([
            'driver_id' => $driver3->id,
            'trip_type' => TripType::MRT_TRIP,
        ]);

        Service::create([
            'driver_id' => $driver3->id,
            'trip_type' => TripType::ESP_TRIP,
        ]);

        // Create driver 4 - Water transport service
        $user4 = User::create([
            'username' => 'ECH-25-DRV4P4',
            'phone' => '+213666444444',
            'password' => Hash::make('password'),
            'status' => UserStatus::ACTIVE,
        ]);

        $driver4 = Driver::create([
            'user_id' => $user4->id,
            'federation_id' => null,
            'first_name' => 'Said',
            'last_name' => 'Mokrani',
            'birth_date' => '1987-04-18',
            'email' => 'said.mokrani@example.com',
            'status' => DriverStatus::APPROVED,
        ]);

        Wallet::create([
            'user_id' => $user4->id,
            'balance' => 10000.00,
        ]);

        // Vehicle for driver 4
        if ($vehicleModel && $color) {
            Vehicle::create([
                'driver_id' => $driver4->id,
                'model_id' => $vehicleModel->id,
                'color_id' => $color->id,
                'production_year' => 2020,
                'plate_number' => '16-456789-20',
            ]);
        }

        // Cards for driver 4
        Card::create([
            'driver_id' => $driver4->id,
            'type' => CardType::NATIONAL_ID,
            'number' => '198712345678904',
            'expiration_date' => now()->addYears(5),
        ]);

        Card::create([
            'driver_id' => $driver4->id,
            'type' => CardType::DRIVING_LICENSE,
            'number' => 'DL999888777',
            'expiration_date' => now()->addYears(3),
        ]);

        // Services for driver 4
        Service::create([
            'driver_id' => $driver4->id,
            'trip_type' => TripType::WATER_TRANSPORT,
        ]);

        Service::create([
            'driver_id' => $driver4->id,
            'trip_type' => TripType::CAR_RESCUE,
        ]);
    }
}
