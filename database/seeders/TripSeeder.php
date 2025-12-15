<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Cargo;
use App\Models\Guest;
use App\Models\Driver;
use App\Models\Wilaya;
use App\Models\Location;
use App\Models\Passenger;
use App\Models\TripCargo;
use App\Models\TripClient;
use App\Models\TripReview;
use App\Constants\RideType;
use App\Constants\TripType;
use App\Constants\Direction;
use App\Constants\WaterType;
use App\Constants\TripStatus;
use App\Models\TaxiRideDetail;
use App\Models\CarRescueDetail;
use Illuminate\Database\Seeder;
use App\Constants\MalfunctionType;
use App\Models\CargoTransportDetail;
use App\Models\WaterTransportDetail;
use App\Models\InternationalTripDetail;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = Driver::all();
        $passengers = Passenger::all();

        // Trip 1: Taxi Ride (completed)
        $driver1 = $drivers->where('first_name', 'Karim')->first();
        $passenger1 = $passengers->where('first_name', 'Ahmed')->first();

        if ($driver1 && $passenger1) {
            // Create location for taxi ride
            $startLocation = Location::create([
                'name' => 'Aeroport Houari Boumediene',
                'address' => 'Dar El Beida, Algiers',
                'latitude' => 36.691014,
                'longitude' => 3.215408,
            ]);

            $endLocation = Location::create([
                'name' => 'Centre Ville Alger',
                'address' => 'Grande Poste, Algiers',
                'latitude' => 36.753768,
                'longitude' => 3.058756,
            ]);

            $taxiDetail = TaxiRideDetail::create([
                'starting_point_id' => $startLocation->id,
                'starting_point_type' => Location::class,
                'arrival_point_id' => $endLocation->id,
                'arrival_point_type' => Location::class,
                'ride_type' => RideType::PRIVATE,
            ]);

            $trip1 = Trip::create([
                'driver_id' => $driver1->id,
                'identifier' => 'TRP-25-TX1A2B',
                'type' => TripType::TAXI_RIDE,
                'status' => TripStatus::COMPLETED,
                'detailable_id' => $taxiDetail->id,
                'detailable_type' => TaxiRideDetail::class,
                'note' => 'Airport pickup service',
            ]);

            TripClient::create([
                'trip_id' => $trip1->id,
                'client_id' => $passenger1->id,
                'client_type' => Passenger::class,
                'number_of_seats' => 1,
                'total_fees' => 1500.00,
            ]);

            // Passenger reviews driver
            TripReview::create([
                'trip_id' => $trip1->id,
                'reviewer_id' => $passenger1->id,
                'reviewer_type' => Passenger::class,
                'reviewee_id' => $driver1->id,
                'reviewee_type' => Driver::class,
                'rating' => 5,
                'comment' => 'Excellent service! Very professional driver.',
            ]);

            // Driver reviews passenger
            TripReview::create([
                'trip_id' => $trip1->id,
                'reviewer_id' => $driver1->id,
                'reviewer_type' => Driver::class,
                'reviewee_id' => $passenger1->id,
                'reviewee_type' => Passenger::class,
                'rating' => 5,
                'comment' => 'Great passenger, very polite and on time.',
            ]);
        }

        // Trip 2: Cargo Transport (ongoing)
        $driver2 = $drivers->where('first_name', 'Rachid')->first();
        $passenger2 = $passengers->where('first_name', 'Fatima')->first();

        if ($driver2 && $passenger2) {
            $deliveryLocation = Location::create([
                'name' => 'Marche de Gros Boufarik',
                'address' => 'Boufarik, Blida',
                'latitude' => 36.574244,
                'longitude' => 2.911377,
            ]);

            $cargoDetail = CargoTransportDetail::create([
                'delivery_point_id' => $deliveryLocation->id,
                'delivery_time' => now()->addHours(3),
            ]);

            $trip2 = Trip::create([
                'driver_id' => $driver2->id,
                'identifier' => 'TRP-25-CG2C3D',
                'type' => TripType::CARGO_TRANSPORT,
                'status' => TripStatus::ONGOING,
                'detailable_id' => $cargoDetail->id,
                'detailable_type' => CargoTransportDetail::class,
                'note' => 'Urgent delivery - fresh produce',
            ]);

            // Create cargo
            $cargo = Cargo::create([
                'passenger_id' => $passenger2->id,
                'description' => 'Fresh vegetables and fruits - 5 boxes',
                'weight' => 150.50,
            ]);

            TripCargo::create([
                'trip_id' => $trip2->id,
                'cargo_id' => $cargo->id,
                'total_fees' => 3500.00,
            ]);
        }

        // Trip 3: International Trip to Spain (MRT - pending)
        $driver3 = $drivers->where('first_name', 'Youcef')->first();
        $passenger1 = $passengers->where('first_name', 'Ahmed')->first();
        $passenger3 = $passengers->where('first_name', 'Mohammed')->first();

        if ($driver3) {
            $mrtDetail = InternationalTripDetail::create([
                'direction' => Direction::TO,
                'starting_place' => 'Maghnia Border',
                'starting_time' => now()->addDays(2),
                'arrival_time' => now()->addDays(2)->addHours(6),
                'total_seats' => 7,
                'seat_price' => 5000.00,
            ]);

            $trip3 = Trip::create([
                'driver_id' => $driver3->id,
                'identifier' => 'TRP-25-MR3E4F',
                'type' => TripType::MRT_TRIP,
                'status' => TripStatus::PENDING,
                'detailable_id' => $mrtDetail->id,
                'detailable_type' => InternationalTripDetail::class,
                'note' => 'Direct trip to Morocco, comfortable seats',
            ]);

            // Add passengers
            if ($passenger1) {
                TripClient::create([
                    'trip_id' => $trip3->id,
                    'client_id' => $passenger1->id,
                    'client_type' => Passenger::class,
                    'number_of_seats' => 2,
                    'total_fees' => 10000.00,
                    'note' => 'Family trip - 2 seats',
                ]);
            }

            if ($passenger3) {
                TripClient::create([
                    'trip_id' => $trip3->id,
                    'client_id' => $passenger3->id,
                    'client_type' => Passenger::class,
                    'number_of_seats' => 1,
                    'total_fees' => 5000.00,
                ]);
            }
        }

        // Trip 4: International Trip to Spain (ESP - completed)
        if ($driver3) {
            $espDetail = InternationalTripDetail::create([
                'direction' => Direction::TO,
                'starting_place' => 'Oran Port',
                'starting_time' => now()->subDays(5),
                'arrival_time' => now()->subDays(4),
                'total_seats' => 6,
                'seat_price' => 8000.00,
            ]);

            $trip4 = Trip::create([
                'driver_id' => $driver3->id,
                'identifier' => 'TRP-25-ES4G5H',
                'type' => TripType::ESP_TRIP,
                'status' => TripStatus::COMPLETED,
                'detailable_id' => $espDetail->id,
                'detailable_type' => InternationalTripDetail::class,
                'note' => 'Ferry trip to Spain',
            ]);

            $passenger2 = $passengers->where('first_name', 'Fatima')->first();
            if ($passenger2) {
                TripClient::create([
                    'trip_id' => $trip4->id,
                    'client_id' => $passenger2->id,
                    'client_type' => Passenger::class,
                    'number_of_seats' => 1,
                    'total_fees' => 8000.00,
                ]);

                // Passenger reviews driver
                TripReview::create([
                    'trip_id' => $trip4->id,
                    'reviewer_id' => $passenger2->id,
                    'reviewer_type' => Passenger::class,
                    'reviewee_id' => $driver3->id,
                    'reviewee_type' => Driver::class,
                    'rating' => 4,
                    'comment' => 'Good trip, arrived on time.',
                ]);

                // Driver reviews passenger
                TripReview::create([
                    'trip_id' => $trip4->id,
                    'reviewer_id' => $driver3->id,
                    'reviewer_type' => Driver::class,
                    'reviewee_id' => $passenger2->id,
                    'reviewee_type' => Passenger::class,
                    'rating' => 4,
                    'comment' => 'Pleasant passenger, no issues.',
                ]);
            }

            // Add a guest passenger
            $guest = Guest::create([
                'fullname' => 'Omar Belhadj',
                'phone' => '0555999999',
            ]);

            TripClient::create([
                'trip_id' => $trip4->id,
                'client_id' => $guest->id,
                'client_type' => Guest::class,
                'number_of_seats' => 2,
                'total_fees' => 16000.00,
                'note' => 'Guest passenger with family',
            ]);
        }

        // Trip 5: Water Transport (completed)
        $driver4 = $drivers->where('first_name', 'Said')->first();
        $passenger3 = $passengers->where('first_name', 'Mohammed')->first();

        if ($driver4 && $passenger3) {
            // For water transport, we'll store details in the trip notes
            // as WaterTransportDetail has specific fields
            $waterLocation = Location::create([
                'name' => 'Port de Bejaia',
                'address' => 'Bejaia Port',
                'latitude' => 36.753083,
                'longitude' => 5.083611,
            ]);

            $waterTransportDetail = WaterTransportDetail::create([
                'delivery_point_id' => $waterLocation->id,
                'water_type' => WaterType::DRINK,
                'delivery_time' => now()->subDays(1),
                'quantity' => 50
            ]);

            $trip5 = Trip::create([
                'driver_id' => $driver4->id,
                'identifier' => 'TRP-25-WT5I6J',
                'type' => TripType::WATER_TRANSPORT,
                'status' => TripStatus::COMPLETED,
                'detailable_id' => $waterTransportDetail->id,
                'detailable_type' => WaterTransportDetail::class,
                'note' => 'Water cistern delivery - 50 liters',
            ]);

            TripClient::create([
                'trip_id' => $trip5->id,
                'client_id' => $passenger3->id,
                'client_type' => Passenger::class,
                'number_of_seats' => 1,
                'total_fees' => 2500.00,
            ]);

            // Passenger reviews driver
            TripReview::create([
                'trip_id' => $trip5->id,
                'reviewer_id' => $passenger3->id,
                'reviewer_type' => Passenger::class,
                'reviewee_id' => $driver4->id,
                'reviewee_type' => Driver::class,
                'rating' => 5,
                'comment' => 'Quick delivery, good quality water.',
            ]);

            // Driver reviews passenger
            TripReview::create([
                'trip_id' => $trip5->id,
                'reviewer_id' => $driver4->id,
                'reviewer_type' => Driver::class,
                'reviewee_id' => $passenger3->id,
                'reviewee_type' => Passenger::class,
                'rating' => 5,
                'comment' => 'Very cooperative and easy to work with.',
            ]);
        }

        // Trip 6: Car Rescue (completed)
        if ($driver4 && $passenger1) {
            $rescueLocation = Location::create([
                'name' => 'Autoroute Est-Ouest',
                'address' => 'KM 120, near Sidi Bel Abbes',
                'latitude' => 35.190556,
                'longitude' => -0.640556,
            ]);

            $carRescueDetail = CarRescueDetail::create([
                'breakdown_point_id' => $rescueLocation->id,
                'malfunction_type' => MalfunctionType::TIRE,
                'delivery_time' => now()->subDays(1),
            ]);

            $trip6 = Trip::create([
                'driver_id' => $driver4->id,
                'identifier' => 'TRP-25-RC6K7L',
                'type' => TripType::CAR_RESCUE,
                'status' => TripStatus::COMPLETED,
                'detailable_id' => $carRescueDetail->id,
                'detailable_type' => CarRescueDetail::class,
                'note' => 'Engine failure - towed to nearest garage',
            ]);

            TripClient::create([
                'trip_id' => $trip6->id,
                'client_id' => $passenger1->id,
                'client_type' => Passenger::class,
                'number_of_seats' => 1,
                'total_fees' => 4000.00,
            ]);

            // Passenger reviews driver
            TripReview::create([
                'trip_id' => $trip6->id,
                'reviewer_id' => $passenger1->id,
                'reviewer_type' => Passenger::class,
                'reviewee_id' => $driver4->id,
                'reviewee_type' => Driver::class,
                'rating' => 5,
                'comment' => 'Saved my day! Very responsive and professional.',
            ]);

            // Driver reviews passenger
            TripReview::create([
                'trip_id' => $trip6->id,
                'reviewer_id' => $driver4->id,
                'reviewer_type' => Driver::class,
                'reviewee_id' => $passenger1->id,
                'reviewee_type' => Passenger::class,
                'rating' => 5,
                'comment' => 'Patient customer during the rescue process.',
            ]);
        }

        // Trip 7: Multiple passengers and cargo
        if ($driver2) {
            $multiDelivery = Location::create([
                'name' => 'Zone Industrielle Rouiba',
                'address' => 'Rouiba, Algiers',
                'latitude' => 36.738889,
                'longitude' => 3.283333,
            ]);

            $cargoDetail2 = CargoTransportDetail::create([
                'delivery_point_id' => $multiDelivery->id,
                'delivery_time' => now()->subDays(1),
            ]);

            $trip7 = Trip::create([
                'driver_id' => $driver2->id,
                'identifier' => 'TRP-25-CG7M8N',
                'type' => TripType::CARGO_TRANSPORT,
                'status' => TripStatus::COMPLETED,
                'detailable_id' => $cargoDetail2->id,
                'detailable_type' => CargoTransportDetail::class,
                'note' => 'Industrial equipment delivery',
            ]);

            // Multiple cargos from different passengers
            $passenger2 = $passengers->where('first_name', 'Fatima')->first();
            $passenger3 = $passengers->where('first_name', 'Mohammed')->first();

            if ($passenger2) {
                $cargo1 = Cargo::create([
                    'passenger_id' => $passenger2->id,
                    'description' => 'Electronics - 3 cartons',
                    'weight' => 45.00,
                ]);

                TripCargo::create([
                    'trip_id' => $trip7->id,
                    'cargo_id' => $cargo1->id,
                    'total_fees' => 1500.00,
                ]);
            }

            if ($passenger3) {
                $cargo2 = Cargo::create([
                    'passenger_id' => $passenger3->id,
                    'description' => 'Spare parts - 2 boxes',
                    'weight' => 30.00,
                ]);

                TripCargo::create([
                    'trip_id' => $trip7->id,
                    'cargo_id' => $cargo2->id,
                    'total_fees' => 1200.00,
                ]);
            }
        }
    }
}
