<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Constants\TripStatus;
use App\Services\TripService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Http\Requests\Api\Trip\CreateTripRequest;
use App\Http\Requests\Api\Trip\UpdateTripRequest;
use App\Http\Requests\Api\Trip\AvailableTripsRequest;

class TripController extends Controller
{
    use ApiResponseTrait;

    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * Get trips by type for the authenticated user (driver or passenger)
     */
    public function index(Request $request, string $type): JsonResponse
    {
        try {
            // Validate trip type
            if (!in_array($type, TripType::all())) {
                throw new Exception('Invalid trip type');
            }

            $user = auth()->user();
            $filters = $this->buildFilters($request, $type);

            // Determine if user is accessing as driver or passenger based on route
            $isDriverRoute = $request->route()->getPrefix() === 'api/v1/driver';

            if ($isDriverRoute) {
                $trips = $this->getDriverTrips($type, $filters, $user);
            } else {
                $trips = $this->getPassengerTrips($type, $filters, $user);
            }

            return $this->successResponse(TripResource::collection($trips));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get trips for driver
     */
    protected function getDriverTrips(string $type, array $filters, $user)
    {
        $driver = $user->driver;

        if (!$driver) {
            throw new Exception('Driver profile not found');
        }

        $query = $this->tripService->getDriverTrips($type, $filters, $driver->id);

        return $query->paginate(10);
    }

    /**
     * Get trips for passenger
     */
    protected function getPassengerTrips(string $type, array $filters, $user)
    {
        $passenger = $user->passenger;

        if (!$passenger) {
            throw new Exception('Passenger profile not found');
        }

        $query = $this->tripService->getPassengerTrips($type, $filters, $passenger->id);

        return $query->paginate(10);
    }

    /**
     * Build filters array from request parameters
     */
    protected function buildFilters(Request $request, string $type): array
    {
        $filters = [];

        // Common filters for all trip types
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }

        if ($request->has('created_at_from') || $request->has('created_at_to')) {
            $filters['created_at'] = [];
            if ($request->has('created_at_from')) {
                $filters['created_at']['from'] = $request->input('created_at_from');
            }
            if ($request->has('created_at_to')) {
                $filters['created_at']['to'] = $request->input('created_at_to');
            }
        }

        // Trip type specific filters
        switch ($type) {
            case TripType::TAXI_RIDE:
                if ($request->has('type') && in_array($request->input('type'), ['shared', 'private'])) {
                    $filters['type'] = $request->input('type');
                }
                break;

            case TripType::CAR_RESCUE:
                if ($request->has('malfunction_type') && in_array($request->input('malfunction_type'), ['tire', 'fuel', 'battery', 'other'])) {
                    $filters['malfunction_type'] = $request->input('malfunction_type');
                }
                break;

            case TripType::PAID_DRIVING:
                if ($request->has('vehicle_type') && in_array($request->input('vehicle_type'), ['car', 'truck'])) {
                    $filters['vehicle_type'] = $request->input('vehicle_type');
                }
                break;

            case TripType::WATER_TRANSPORT:
                if ($request->has('water_type') && in_array($request->input('water_type'), ['drink', 'tea'])) {
                    $filters['water_type'] = $request->input('water_type');
                }
                break;

            case TripType::MRT_TRIP:
            case TripType::ESP_TRIP:
                if ($request->has('direction')) {
                    $filters['direction'] = $request->input('direction');
                }

                if ($request->has('type') && in_array($request->input('type'), ['cargo', 'client'])) {
                    $filters['type'] = $request->input('type');
                }
                break;
        }

        return $filters;
    }

    /**
     * Create a new trip
     */
    public function store(CreateTripRequest $request, string $type): JsonResponse
    {

        $validated = $this->validateRequest($request);

        try {
            // Validate trip type
            if (!in_array($type, TripType::all())) {
                throw new Exception('Invalid trip type');
            }

            $trip = $this->tripService->createTrip($type, $validated, auth()->user());

            return $this->successResponse(
                new TripResource($trip)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a trip (only by driver)
     */
    public function update(UpdateTripRequest $request, $id): JsonResponse
    {
        try {
            $user = auth()->user();
            $driver = $user->driver;
            $trip = Trip::findOrFail($id);

            if (!$driver) {
                throw new Exception('Driver profile not found');
            }

            // Verify the driver owns this trip
            if ($trip->driver_id !== $driver->id) {
                throw new Exception('Unauthorized to update this trip', 403);
            }

            $validated = $this->validateRequest($request);
            $tripType = $trip->type;

            // For international trips, check if details can be updated
            if (in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                $detailsData = array_intersect_key($validated, array_flip([
                    'direction',
                    'starting_place',
                    'starting_time',
                    'arrival_time',
                    'total_seats',
                    'seat_price'
                ]));

                if (!empty($detailsData)) {
                    // Check if starting time has passed
                    if ($trip->detailable && $trip->detailable->starting_time <= now()) {
                        throw new Exception('Cannot update international trip details after starting time', 400);
                    }
                }
            }

            $updatedTrip = $this->tripService->updateTrip($trip, $validated, $tripType);

            return $this->successResponse(
                new TripResource($updatedTrip)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a trip (only international trips with restrictions)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $driver = $user->driver;
            $trip = Trip::findOrFail($id);

            if (!$driver) {
                throw new Exception('Driver profile not found');
            }

            // Verify the driver owns this trip
            if ($trip->driver_id !== $driver->id) {
                throw new Exception('Unauthorized to delete this trip', 403);
            }

            $tripType = $trip->type;

            // Only allow deletion of international trips
            if (!in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('Only international trips can be deleted');
            }

            // Check if starting time has passed
            if ($trip->detailable && $trip->detailable->starting_time <= now()) {
                throw new Exception('Cannot delete international trip after starting time');
            }

            // Check if trip has clients or cargos
            $hasClients = $trip->clients()->exists();
            $hasCargos = $trip->cargos()->exists();

            if ($hasClients || $hasCargos) {
                throw new Exception('Cannot delete trip with existing clients or cargos');
            }

            $this->tripService->deleteTrip($trip);

            return $this->successResponse();

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get available international trips
     */
    public function available(AvailableTripsRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            $availableTrips = $this->tripService->getAvailableInternationalTrips(
                $validated['trip_type'],
                $validated['starting_time'],
                $validated['number_of_seats'] ?? null
            );

            return $this->successResponse(
                TripResource::collection($availableTrips)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}