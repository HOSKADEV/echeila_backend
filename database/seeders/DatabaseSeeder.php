<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AdminSeeder::class,
            DocumentationSeeder::class,
            BrandSeeder::class,
            VehicleModelSeeder::class,
            ColorSeeder::class,
            WilayaSeeder::class,
            SeatPriceSeeder::class,
            FederationSeeder::class,
            PassengerSeeder::class,
            DriverSeeder::class,
            TripSeeder::class,
            LostAndFoundSeeder::class,
        ]);
    }
}
