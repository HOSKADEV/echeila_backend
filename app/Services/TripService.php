<?php

namespace App\Services;

use Exception;
use App\Models\Trip;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Driver;
use App\Models\Location;
use App\Models\Passenger;
use App\Models\TripCargo;
use App\Models\TripClient;
use App\Constants\RideType;
use App\Constants\TripType;
use App\Traits\RandomTrait;
use App\Traits\ImageUpload;
use App\Constants\TripStatus;
use App\Models\TaxiRideDetail;
use App\Models\CarRescueDetail;
use App\Models\PaidDrivingDetail;
use Illuminate\Support\Facades\DB;
use App\Models\CargoTransportDetail;
use App\Models\WaterTransportDetail;
use App\Constants\NotificationMessages;
use App\Models\InternationalTripDetail;
use App\Notifications\NewMessageNotification;

class TripService
{

    use RandomTrait, ImageUpload;
    /**
     * Create a new trip with its details and related data
     */
    public function createTrip(string $tripType, array $data, User $user): Trip
    {
        return DB::transaction(function () use ($tripType, $data, $user) {
            // Determine driver_id based on trip type
            $driver = $this->handleDriver($tripType, $data, $user);

            // Create trip details first
            $detailsModel = $this->handleTripDetails($tripType, $data, $user);

            do {
                $trip_identifier = 'TRP-'.now()->format('y')."-{$this->random(6, 'uppercase_alphanumeric')}";
            } while (Trip::where('identifier', $trip_identifier)->exists());

            // Create the main trip with polymorphic relationship
            $trip = Trip::create([
                'driver_id' => $driver->id,
                'identifier' => $trip_identifier,
                'type' => $tripType,
                'status' => TripStatus::PENDING,
                'note' => $data['note'] ?? null,
                'detailable_id' => $detailsModel->id,
                'detailable_type' => get_class($detailsModel),
            ]);

            // Add current user as client for non-international trips
            $this->handleClient($trip, $tripType, $data, $user);
            
            // Handle cargo creation and relationship for cargo transport trips
            $this->handleCargo($trip, $tripType, $data, $user);

            if(!in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                // Send notification
                $driver->user->notify(new NewMessageNotification(
                    NotificationMessages::TRIP_PENDING,
                    ['trip_id' => $trip->id, 'trip_type' => $tripType]
                ));
            }

            // Load relevant relationships based on trip type
            switch ($tripType) {
                case TripType::TAXI_RIDE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::CAR_RESCUE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.breakdownPoint'
                    ]);
                    break;

                case TripType::CARGO_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'cargo.cargo',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::WATER_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::PAID_DRIVING:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::MRT_TRIP:
                case TripType::ESP_TRIP:
                    $trip->load([
                        'driver',
                    ]);
                    break;

                default:
                    $trip->load(['driver', 'detailable']);
                    break;
            }

            return $trip;
        });
    }

    /**
     * Create trip-specific details based on trip type
     */
    protected function handleTripDetails(string $tripType, array $data, User $user)
    {
        return match ($tripType) {
            TripType::TAXI_RIDE => $this->createTaxiRideDetail($data),
            TripType::CAR_RESCUE => $this->createCarRescueDetail($data),
            TripType::CARGO_TRANSPORT => $this->createCargoTransportDetail($data),
            TripType::WATER_TRANSPORT => $this->createWaterTransportDetail($data),
            TripType::PAID_DRIVING => $this->createPaidDrivingDetail($data),
            TripType::MRT_TRIP, TripType::ESP_TRIP => $this->createInternationalTripDetail($data),
        };
    }

    /**
     * Determine the driver ID based on trip type and provided data
     */
    protected function handleDriver(string $tripType, array $data, User $user): Driver
    {
        if (in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
            $data['driver_id'] = $data['driver_id'] ?? $user->driver->id;
        }

        $driver = Driver::findOrFail($data['driver_id']);

        if($driver->services()->where('trip_type',$tripType)->doesntExist()){
            throw new Exception("This driver does not provide {$tripType} service");
        }

        return $driver;
    }

    /**
     * Handle adding current user as trip client for non-international trips
     */
    protected function handleClient(Trip $trip, string $tripType, array $data, User $user): void
    {
        if(!in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {

            TripClient::create([
                'trip_id' => $trip->id,
                'client_id' => $user->passenger->id,
                'client_type' => Passenger::class,
                'number_of_seats' => $data['number_of_seats'] ?? 1,
                'total_fees' => $data['total_fees'] ?? 0,
                'note' => $data['client_note'] ?? null,
            ]);
        }
        
    }

   /**
     * Handle cargo creation and relationship for cargo transport trips
     */
    protected function handleCargo(Trip $trip, string $tripType, array $data, User $user): void
    {
        if ($tripType === TripType::CARGO_TRANSPORT) {
            // Create the cargo record with description, weight, and passenger_id
            $cargo = Cargo::create([
                'description' => $data['cargo']['description'],
                'weight' => $data['cargo']['weight'],
                'passenger_id' => $user->passenger->id,
            ]);

            // Handle cargo images if provided using ImageUpload trait
            if (isset($data['cargo']['images']) && is_array($data['cargo']['images'])) {
                foreach ($data['cargo']['images'] as $image) {
                    if ($image && $image->isValid()) {
                        $this->uploadImage($cargo, $image, Cargo::IMAGES);
                    }
                }
            }

            // Create trip cargo relationship
            TripCargo::create([
                'trip_id' => $trip->id,
                'cargo_id' => $cargo->id,
                'total_fees' => $data['total_fees'] ?? 0,
            ]);
        }
    }


    /**
     * Create taxi ride details
     */
    protected function createTaxiRideDetail(array $data): TaxiRideDetail
    {
        $rideType = $data['ride_type'];

        if ($rideType === RideType::PRIVATE) {
            // For private rides, create Location records from coordinate data
            $startingLocation = Location::create([
                'name' => $data['starting_point']['name'],
                'latitude' => $data['starting_point']['latitude'],
                'longitude' => $data['starting_point']['longitude'],
            ]);

            $arrivalLocation = Location::create([
                'name' => $data['arrival_point']['name'],
                'latitude' => $data['arrival_point']['latitude'],
                'longitude' => $data['arrival_point']['longitude'],
            ]);

            return TaxiRideDetail::create([
                'starting_point_id' => $startingLocation->id,
                'starting_point_type' => Location::class,
                'arrival_point_id' => $arrivalLocation->id,
                'arrival_point_type' => Location::class,
                'ride_type' => $rideType,
            ]);
        } else {
            // For shared rides, use wilaya IDs
            return TaxiRideDetail::create([
                'starting_point_id' => $data['starting_point_id'],
                'starting_point_type' => 'App\\Models\\Wilaya',
                'arrival_point_id' => $data['arrival_point_id'],
                'arrival_point_type' => 'App\\Models\\Wilaya',
                'ride_type' => $rideType,
            ]);
        }
    }

    /**
     * Create car rescue details
     */
    protected function createCarRescueDetail(array $data): CarRescueDetail
    {
        // Create Location record from coordinate data
        $breakdownLocation = Location::create([
            'name' => $data['breakdown_point']['name'],
            'latitude' => $data['breakdown_point']['latitude'],
            'longitude' => $data['breakdown_point']['longitude'],
        ]);

        return CarRescueDetail::create([
            'breakdown_point_id' => $breakdownLocation->id,
            'delivery_time' => $data['delivery_time'],
            'malfunction_type' => $data['malfunction_type'],
        ]);
    }

    /**
     * Create cargo transport details
     */
    protected function createCargoTransportDetail(array $data): CargoTransportDetail
    {

        // Create Location record from coordinate data
        $deliveryLocation = Location::create([
            'name' => $data['delivery_point']['name'],
            'latitude' => $data['delivery_point']['latitude'],
            'longitude' => $data['delivery_point']['longitude'],
        ]);

        // Create the cargo transport detail
        $cargoTransportDetail = CargoTransportDetail::create([
            'delivery_point_id' => $deliveryLocation->id,
            'delivery_time' => $data['delivery_time'],
        ]);

        return $cargoTransportDetail;
    }

    /**
     * Create water transport details
     */
    protected function createWaterTransportDetail(array $data): WaterTransportDetail
    {
        // Create Location record from coordinate data
        $deliveryLocation = Location::create([
            'name' => $data['delivery_point']['name'],
            'latitude' => $data['delivery_point']['latitude'],
            'longitude' => $data['delivery_point']['longitude'],
        ]);

        return WaterTransportDetail::create([
            'delivery_point_id' => $deliveryLocation->id,
            'delivery_time' => $data['delivery_time'],
            'water_type' => $data['water_type'],
            'quantity' => $data['quantity'],
        ]);
    }

    /**
     * Create paid driving details
     */
    protected function createPaidDrivingDetail(array $data): PaidDrivingDetail
    {
        // Create Location records from coordinate data
        $startingLocation = Location::create([
            'name' => $data['starting_point']['name'],
            'latitude' => $data['starting_point']['latitude'],
            'longitude' => $data['starting_point']['longitude'],
        ]);

        $arrivalLocation = Location::create([
            'name' => $data['arrival_point']['name'],
            'latitude' => $data['arrival_point']['latitude'],
            'longitude' => $data['arrival_point']['longitude'],
        ]);

        return PaidDrivingDetail::create([
            'starting_point_id' => $startingLocation->id,
            'arrival_point_id' => $arrivalLocation->id,
            'starting_time' => $data['starting_time'],
            'vehicle_type' => $data['vehicle_type'],
        ]);
    }

    /**
     * Create international trip details
     */
    protected function createInternationalTripDetail(array $data): InternationalTripDetail
    {
        return InternationalTripDetail::create([
            'direction' => $data['direction'],
            'starting_place' => $data['starting_place'],
            'starting_time' => $data['starting_time'],
            'arrival_time' => $data['arrival_time'],
            'total_seats' => $data['total_seats'],
            'seat_price' => $data['seat_price'],
        ]);
    }

    /**
     * Create trip clients
     */
    protected function createTripClients(Trip $trip, array $clients): void
    {
        foreach ($clients as $clientData) {
            TripClient::create([
                'trip_id' => $trip->id,
                'client_id' => $clientData['client_id'],
                'client_type' => $clientData['client_type'],
                'number_of_seats' => $clientData['number_of_seats'] ?? 1,
                'total_fees' => $clientData['total_fees'],
                'note' => $clientData['note'] ?? null,
            ]);
        }
    }

    /**
     * Create trip cargos
     */
    protected function createTripCargos(Trip $trip, array $cargos): void
    {
        foreach ($cargos as $cargoData) {
            TripCargo::create([
                'trip_id' => $trip->id,
                'cargo_id' => $cargoData['cargo_id'],
                'total_fees' => $cargoData['total_fees'],
            ]);
        }
    }

    /**
     * Get trips by type with filters for drivers
     */
    public function getDriverTrips(string $tripType, array $filters, int $driverId)
    {
        return match ($tripType) {
            TripType::TAXI_RIDE => $this->getTaxiRideTrips($filters, $driverId),
            TripType::CAR_RESCUE => $this->getCarRescueTrips($filters, $driverId),
            TripType::CARGO_TRANSPORT => $this->getCargoTransportTrips($filters, $driverId),
            TripType::WATER_TRANSPORT => $this->getWaterTransportTrips($filters, $driverId),
            TripType::PAID_DRIVING => $this->getPaidDrivingTrips($filters, $driverId),
            TripType::MRT_TRIP => $this->getMrtTrips($filters, $driverId),
            TripType::ESP_TRIP => $this->getEspTrips($filters, $driverId),
        };
    }

    /**
     * Get trips by type with filters for passengers
     */
    public function getPassengerTrips(string $tripType, array $filters, int $passengerId)
    {
        return match ($tripType) {
            TripType::TAXI_RIDE => $this->getTaxiRideTripsForPassenger($filters, $passengerId),
            TripType::CAR_RESCUE => $this->getCarRescueTripsForPassenger($filters, $passengerId),
            TripType::CARGO_TRANSPORT => $this->getCargoTransportTripsForPassenger($filters, $passengerId),
            TripType::WATER_TRANSPORT => $this->getWaterTransportTripsForPassenger($filters, $passengerId),
            TripType::PAID_DRIVING => $this->getPaidDrivingTripsForPassenger($filters, $passengerId),
            TripType::MRT_TRIP => $this->getMrtTripsForPassenger($filters, $passengerId),
            TripType::ESP_TRIP => $this->getEspTripsForPassenger($filters, $passengerId),
        };
    }

    /**
     * Get taxi ride trips for drivers
     */
    protected function getTaxiRideTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::TAXI_RIDE)
            ->with([
                'driver',
                'clients.client.user',
                'detailable.startingPoint',
                'detailable.arrivalPoint'
            ]);

        // Apply taxi ride specific filters
        if (isset($filters['type']) && in_array($filters['type'], ['shared', 'private'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('ride_type', $filters['type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get car rescue trips for drivers
     */
    protected function getCarRescueTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::CAR_RESCUE)
            ->with([
                'driver',
                'clients.client.user',
                'detailable.breakdownPoint'
            ]);

        // Apply car rescue specific filters
        if (isset($filters['malfunction_type']) && in_array($filters['malfunction_type'], ['tire', 'fuel', 'battery', 'other'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('malfunction_type', $filters['malfunction_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get cargo transport trips for drivers
     */
    protected function getCargoTransportTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::CARGO_TRANSPORT)
            ->with([
                'driver',
                'clients.client.user',
                'cargos.cargo',
                'detailable.deliveryPoint'
            ]);

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get water transport trips for drivers
     */
    protected function getWaterTransportTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::WATER_TRANSPORT)
            ->with([
                'driver',
                'clients.client.user',
                'detailable.deliveryPoint'
            ]);

        // Apply water transport specific filters
        if (isset($filters['water_type']) && in_array($filters['water_type'], ['drink', 'tea'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('water_type', $filters['water_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get paid driving trips for drivers
     */
    protected function getPaidDrivingTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::PAID_DRIVING)
            ->with([
                'driver',
                'clients.client.user',
                'detailable.startingPoint',
                'detailable.arrivalPoint'
            ]);

        // Apply paid driving specific filters
        if (isset($filters['vehicle_type']) && in_array($filters['vehicle_type'], ['car', 'truck'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('vehicle_type', $filters['vehicle_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get MRT trips for drivers
     */
    protected function getMrtTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::MRT_TRIP)
            ->with([
                'driver',
            ]);

        // Apply common filters
        $query = $this->applyCommonFilters($query, $filters);

        // Apply international-specific filters
        if (isset($filters['direction'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('direction', $filters['direction']);
            });
        }

        if (isset($filters['type']) && in_array($filters['type'], ['cargo', 'client'])) {
            if ($filters['type'] === 'cargo') {
                $query->whereHas('cargos');
            } elseif ($filters['type'] === 'client') {
                $query->whereHas('clients');
            }
        }

        return $query;
    }

    /**
     * Get ESP trips for drivers
     */
    protected function getEspTrips(array $filters, int $driverId)
    {
        $query = Trip::where('driver_id', $driverId)
            ->where('type', TripType::ESP_TRIP)
            ->with([
                'driver',
            ]);

        // Apply common filters
        $query = $this->applyCommonFilters($query, $filters);

        // Apply international-specific filters
        if (isset($filters['direction'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('direction', $filters['direction']);
            });
        }

        if (isset($filters['type']) && in_array($filters['type'], ['cargo', 'client'])) {
            if ($filters['type'] === 'cargo') {
                $query->whereHas('cargos');
            } elseif ($filters['type'] === 'client') {
                $query->whereHas('clients');
            }
        }

        return $query;
    }

    /**
     * Get taxi ride trips for passengers
     */
    protected function getTaxiRideTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::TAXI_RIDE)
            ->whereHas('clients', function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class);
            })
            ->with([
                'driver',
                'clients.client.user',
                'detailable.startingPoint',
                'detailable.arrivalPoint'
            ]);

        // Apply taxi ride specific filters
        if (isset($filters['type']) && in_array($filters['type'], ['shared', 'private'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('ride_type', $filters['type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get car rescue trips for passengers
     */
    protected function getCarRescueTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::CAR_RESCUE)
            ->whereHas('clients', function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class);
            })
            ->with([
                'driver',
                'clients.client.user',
                'detailable.breakdownPoint'
            ]);

        // Apply car rescue specific filters
        if (isset($filters['malfunction_type']) && in_array($filters['malfunction_type'], ['tire', 'fuel', 'battery', 'other'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('malfunction_type', $filters['malfunction_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get cargo transport trips for passengers
     */
    protected function getCargoTransportTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::CARGO_TRANSPORT)
            ->whereHas('clients', function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class);
            })
            ->with([
                'driver',
                'clients.client.user',
                'cargos.cargo',
                'detailable.deliveryPoint'
            ]);

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get water transport trips for passengers
     */
    protected function getWaterTransportTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::WATER_TRANSPORT)
            ->whereHas('clients', function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class);
            })
            ->with([
                'driver',
                'clients.client.user',
                'detailable.deliveryPoint'
            ]);

        // Apply water transport specific filters
        if (isset($filters['water_type']) && in_array($filters['water_type'], ['drink', 'tea'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('water_type', $filters['water_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get paid driving trips for passengers
     */
    protected function getPaidDrivingTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::PAID_DRIVING)
            ->whereHas('clients', function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class);
            })
            ->with([
                'driver',
                'clients.client.user',
                'detailable.startingPoint',
                'detailable.arrivalPoint'
            ]);

        // Apply paid driving specific filters
        if (isset($filters['vehicle_type']) && in_array($filters['vehicle_type'], ['car', 'truck'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('vehicle_type', $filters['vehicle_type']);
            });
        }

        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get MRT trips for passengers
     */
    protected function getMrtTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::MRT_TRIP);

        // Apply type-specific filtering first
        if (isset($filters['type']) && in_array($filters['type'], ['cargo', 'client'])) {
            if ($filters['type'] === 'cargo') {
                // Only trips where passenger is cargo owner
                $query->whereHas('cargos.cargo', function ($q) use ($passengerId) {
                    $q->where('passenger_id', $passengerId);
                });
            } elseif ($filters['type'] === 'client') {
                // Only trips where passenger is a client
                $query->whereHas('clients', function ($q) use ($passengerId) {
                    $q->where('client_id', $passengerId)
                      ->where('client_type', Passenger::class);
                });
            }
        } else {
            // No type filter - show trips where passenger is either client or cargo owner
            $query->where(function ($q) use ($passengerId) {
                $q->whereHas('clients', function ($subQ) use ($passengerId) {
                    $subQ->where('client_id', $passengerId)
                         ->where('client_type', Passenger::class);
                })->orWhereHas('cargos.cargo', function ($subQ) use ($passengerId) {
                    $subQ->where('passenger_id', $passengerId);
                });
            });
        }

        $query->with([
            'driver',
            'client' => function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class)
                  ->with('client.user');
            },
            'cargo' => function ($q) use ($passengerId) {
                $q->whereHas('cargo', function ($subQ) use ($passengerId) {
                    $subQ->where('passenger_id', $passengerId);
                })->with('cargo');
            }
        ]);

        // Apply common filters
        $query = $this->applyCommonFilters($query, $filters);

        // Apply international-specific filters
        if (isset($filters['direction'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('direction', $filters['direction']);
            });
        }

        return $query;
    }

    /**
     * Get ESP trips for passengers
     */
    protected function getEspTripsForPassenger(array $filters, int $passengerId)
    {
        $query = Trip::where('type', TripType::ESP_TRIP);

        // Apply type-specific filtering first
        if (isset($filters['type']) && in_array($filters['type'], ['cargo', 'client'])) {
            if ($filters['type'] === 'cargo') {
                // Only trips where passenger is cargo owner
                $query->whereHas('cargos.cargo', function ($q) use ($passengerId) {
                    $q->where('passenger_id', $passengerId);
                });
            } elseif ($filters['type'] === 'client') {
                // Only trips where passenger is a client
                $query->whereHas('clients', function ($q) use ($passengerId) {
                    $q->where('client_id', $passengerId)
                      ->where('client_type', Passenger::class);
                });
            }
        } else {
            // No type filter - show trips where passenger is either client or cargo owner
            $query->where(function ($q) use ($passengerId) {
                $q->whereHas('clients', function ($subQ) use ($passengerId) {
                    $subQ->where('client_id', $passengerId)
                         ->where('client_type', Passenger::class);
                })->orWhereHas('cargos.cargo', function ($subQ) use ($passengerId) {
                    $subQ->where('passenger_id', $passengerId);
                });
            });
        }

        $query->with([
            'driver',
            'client' => function ($q) use ($passengerId) {
                $q->where('client_id', $passengerId)
                  ->where('client_type', Passenger::class)
                  ->with('client.user');
            },
            'cargo' => function ($q) use ($passengerId) {
                $q->whereHas('cargo', function ($subQ) use ($passengerId) {
                    $subQ->where('passenger_id', $passengerId);
                })->with('cargo');
            }
        ]);

        // Apply common filters
        $query = $this->applyCommonFilters($query, $filters);

        // Apply international-specific filters
        if (isset($filters['direction'])) {
            $query->whereHas('detailable', function ($q) use ($filters) {
                $q->where('direction', $filters['direction']);
            });
        }

        return $query;
    }

    /**
     * Apply common filters (status and created_at) to query
     */
    protected function applyCommonFilters($query, array $filters)
    {
        // Apply status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply created_at filter
        if (isset($filters['created_at'])) {
            if (isset($filters['created_at']['from'])) {
                $query->whereDate('created_at', '>=', $filters['created_at']['from']);
            }
            if (isset($filters['created_at']['to'])) {
                $query->whereDate('created_at', '<=', $filters['created_at']['to']);
            }
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Update a trip
     */
    public function updateTrip(Trip $trip, array $data, string $tripType): Trip
    {
        return DB::transaction(function () use ($trip, $data, $tripType) {
            // Update main trip fields
            $tripData = array_intersect_key($data, array_flip(['status', 'note']));
            if (!empty($tripData)) {
                $trip->update($tripData);

                if ($trip->wasChanged('status') && !in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                    // Notify driver of status change
                    $trip->passenger?->user->notify(new NewMessageNotification(
                        match($trip->status) {
                            TripStatus::CANCELED => NotificationMessages::TRIP_CANCELLED,
                            TripStatus::ONGOING => NotificationMessages::TRIP_ONGOING,
                            TripStatus::COMPLETED => NotificationMessages::TRIP_COMPLETED,
                        },
                        ['trip_id' => $trip->id, 'new_status' => $trip->status]
                    ));
                }
            }

            // Update trip details for international trips if provided
            if (in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                $detailsData = array_intersect_key($data, array_flip([
                    'direction', 'starting_place', 'starting_time', 'arrival_time', 'total_seats', 'seat_price'
                ]));

                if (!empty($detailsData) && $trip->detailable) {
                    $trip->detailable->update($detailsData);
                }
            }

            // Load relevant relationships based on trip type
            switch ($tripType) {
                case TripType::TAXI_RIDE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::CAR_RESCUE:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.breakdownPoint'
                    ]);
                    break;

                case TripType::CARGO_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'cargos.cargo',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::WATER_TRANSPORT:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.deliveryPoint'
                    ]);
                    break;

                case TripType::PAID_DRIVING:
                    $trip->load([
                        'driver',
                        'client.client.user',
                        'detailable.startingPoint',
                        'detailable.arrivalPoint'
                    ]);
                    break;

                case TripType::MRT_TRIP:
                case TripType::ESP_TRIP:
                    $trip->load([
                        'driver',
                        'clients.client',
                        'cargos.cargo',
                        'detailable'
                    ]);
                    break;

                default:
                    $trip->load(['driver', 'detailable']);
                    break;
            }

            return $trip;
        });
    }

    /**
     * Delete a trip
     */
    public function deleteTrip(Trip $trip): bool
    {
        return DB::transaction(function () use ($trip) {
            // Delete the trip details first (polymorphic relationship)
            if ($trip->detailable) {
                $trip->detailable->delete();
            }

            // Delete the trip itself
            return $trip->delete();
        });
    }

    /**
     * Get available international trips based on criteria
     */
    public function getAvailableInternationalTrips(string $tripType, string $startingTime, ?int $requiredSeats = null)
    {
        return Trip::where('type', $tripType)
            ->where('status', TripStatus::PENDING)
            ->with([
                'driver',
                'clients',
                'detailable'
            ])
            ->get()
            ->map(function ($trip) {
                // Calculate available seats for all trips
                if ($trip->detailable) {
                    $totalSeats = $trip->detailable->total_seats;
                    $bookedSeats = $trip->clients->sum('number_of_seats');
                    $availableSeats = $totalSeats - $bookedSeats;
                    
                    // Add available_seats to the trip object for response
                    $trip->available_seats = $availableSeats;
                }
                
                return $trip;
            })
            ->filter(function ($trip) use ($startingTime, $requiredSeats) {
                // Check if trip has detailable (international trip details)
                if (!$trip->detailable) {
                    return false;
                }
                
                // Check datetime conditions
                $tripStartingTime = $trip->detailable->starting_time;
                if ($tripStartingTime <= now() || $tripStartingTime < $startingTime) {
                    return false;
                }
                
                // Check if enough seats are available (only if requiredSeats is provided)
                if ($requiredSeats !== null) {
                    return $trip->available_seats >= $requiredSeats;
                }
                
                return true;
            });
    }
}