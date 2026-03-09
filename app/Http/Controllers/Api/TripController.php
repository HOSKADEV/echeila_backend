<?php

namespace App\Http\Controllers\Api;

use App\Constants\CancellationReason;
use App\Constants\NotificationMessages;
use App\Constants\TripStatus;
use App\Constants\TripType;
use App\Constants\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Trip\AvailableTripsRequest;
use App\Http\Requests\Api\Trip\CreateTripRequest;
use App\Http\Requests\Api\Trip\UpdateTripRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Notifications\NewMessageNotification;
use App\Services\TripService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            // Send notification to driver for non-international trips
            if(!in_array($type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                try {
                    $trip->load('driver.user');
                    $trip->driver->user->notify(new NewMessageNotification(
                        NotificationMessages::TRIP_PENDING,
                        ['trip_id' => $trip->id, 'trip_type' => $type]
                    ));
                } catch (Exception $notificationException) {
                    // Log the error but don't fail the trip creation
                    Log::warning('Failed to send notification: ' . $notificationException->getMessage());
                }
            }

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
            $user      = auth()->user();
            $driver    = $user->driver;
            $passenger = $user->passenger;
            $trip      = Trip::findOrFail($id);

            $isDriver    = $driver && $trip->driver_id === $driver->id;
            $isPassenger = $passenger && $trip->passenger?->id === $passenger->id;

            if (!$isDriver && !$isPassenger) {
                throw new Exception('Unauthorized to update this trip', 403);
            }

            $validated   = $this->validateRequest($request);
            $isCanceling = ($validated['status'] ?? null) === TripStatus::CANCELED;

            // Validate cancellation reason belongs to the correct group
            if ($isCanceling) {
                $reason = $validated['cancellation_reason'] ?? null;
                if ($isPassenger && !$isDriver && $reason && !in_array($reason, CancellationReason::passengerReasons())) {
                    throw new Exception('Invalid cancellation reason for passenger', 422);
                }
                if ($isDriver && $reason && !in_array($reason, CancellationReason::driverReasons())) {
                    throw new Exception('Invalid cancellation reason for driver', 422);
                }
            }

            $tripType = $trip->type;

            // Attach actor identity when canceling
            if ($isCanceling) {
                if ($isDriver) {
                    $validated['canceled_by_type'] = UserType::DRIVER;
                    $validated['canceled_by_id']   = $driver->id;
                } else {
                    $validated['canceled_by_type'] = UserType::PASSENGER;
                    $validated['canceled_by_id']   = $passenger->id;
                }
            }

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

            // Send status-change notifications for non-international trips
            if ($updatedTrip->wasChanged('status') && !in_array($tripType, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                try {
                    if ($isPassenger && !$isDriver) {
                        // Passenger canceled → notify the driver
                        $updatedTrip->driver?->user?->notify(new NewMessageNotification(
                            NotificationMessages::TRIP_CANCELLED,
                            ['trip_id' => $updatedTrip->id, 'new_status' => $updatedTrip->status]
                        ));
                    } else {
                        // Driver changed status → notify the passenger
                        $updatedTrip->client?->client?->user?->notify(new NewMessageNotification(
                            match($updatedTrip->status) {
                                TripStatus::CANCELED  => NotificationMessages::TRIP_CANCELLED,
                                TripStatus::ONGOING   => NotificationMessages::TRIP_ONGOING,
                                TripStatus::COMPLETED => NotificationMessages::TRIP_COMPLETED,
                            },
                            ['trip_id' => $updatedTrip->id, 'new_status' => $updatedTrip->status]
                        ));
                    }
                } catch (Exception $notificationException) {
                    Log::warning('Failed to send notification: ' . $notificationException->getMessage());
                }
            }

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
