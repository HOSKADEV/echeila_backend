<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Models\Guest;
use App\Models\Passenger;
use App\Models\TripClient;
use App\Constants\TripType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\TripStatus;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Constants\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\NotificationMessages;
use App\Http\Resources\TripClientResource;
use App\Notifications\NewMessageNotification;
use App\Http\Requests\Api\TripClient\StoreTripClientRequest;

class TripClientController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get trip clients for a specific trip
     */
    public function index(Request $request)
    {
        $validated = $this->validateRequest($request, [
            'trip_id' => 'required|exists:trips,id'
        ]);

        try {

            $trip = Trip::findOrFail($request->input('trip_id'));

            if (!in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }

            $tripClients = $trip->clients()
                ->with(['client'])
                ->paginate(10);

            return $this->successResponse(
                TripClientResource::collection($tripClients)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a new trip client
     */
    public function store(StoreTripClientRequest $request)
    {
        $validated = $this->validateRequest($request);

        try {

            DB::beginTransaction();

            $user = auth()->user();

            // Get trip and calculate total fees
            $trip = Trip::with('detailable', 'driver.user.wallet')->findOrFail($validated['trip_id']);
            $driver = $trip->driver;

            if (!in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }

            if($validated['number_of_seats'] > $trip->available_seats) {
                throw new Exception('Not enough available seats');
            }

            $seatPrice = $trip->detailable->seat_price;
            $totalFees = $seatPrice * $validated['number_of_seats'];

            // Determine client type and ID
            if ($request->filled('fullname') && $request->filled('phone')) {
                // Create or find guest client
                $guest = Guest::firstOrCreate([
                    'fullname' => $validated['fullname'],
                    'phone' => $validated['phone'],
                ]);

                $clientId = $guest->id;
                $clientType = Guest::class;

            } else {
                // Use current user's passenger as client
                if (!$user->passenger) {
                    throw new Exception('User must have a passenger profile to book a trip');
                }

                if ($trip->clients()->where('client_id', $user->passenger->id)->where('client_type', Passenger::class)->exists()) {
                    throw new Exception('User is already booked on this trip');
                }

                if($totalFees > $user->wallet->balance) {
                    throw new Exception('Insufficient wallet balance');
                }
                // Deduct from user wallet
                $user->wallet->decrement('balance', $totalFees);
                $driver->user->wallet->increment('balance', $totalFees);

                // Create transaction record
                $passengerTransaction = Transaction::create([
                    'wallet_id' => $user->wallet->id,
                    'trip_id' => $validated['trip_id'],
                    'type' => TransactionType::RESERVATION,
                    'amount' => -abs($totalFees),
                ]);

                $driverTransaction = Transaction::create([
                    'wallet_id' => $driver->user->wallet->id,
                    'trip_id' => $validated['trip_id'],
                    'type' => TransactionType::RESERVATION,
                    'amount' => abs($totalFees)
                ]);

            
             // Send notifications
            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_RESERVATION,
                data: ['amount' => $passengerTransaction->amount, 'balance' => $user->wallet->balance]
            ));

            $driver->user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_RESERVATION,
                data: ['amount' => $driverTransaction->amount, 'balance' => $driver->user->wallet->balance]
            ));

                $clientId = $user->passenger->id;
                $clientType = Passenger::class;


            }

                // Create trip client
            $tripClient = TripClient::create([
                'trip_id' => $validated['trip_id'],
                'client_id' => $clientId,
                'client_type' => $clientType,
                'number_of_seats' => $validated['number_of_seats'],
                'total_fees' => $totalFees,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();

            $tripClient->load(['trip', 'client']);

            return $this->successResponse(
                new TripClientResource($tripClient)
            );

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a trip client
     */
    public function destroy($id): JsonResponse
    {
        try {

            DB::beginTransaction();
            // Check if the authenticated user is the client or the trip driver
            $user = auth()->user();
            $tripClient = TripClient::findOrFail($id);

            $isClient = false;
            $isDriver = false;

            // Check if user is the client (only for passenger clients, not guests)
            if ($tripClient->client_type === Passenger::class && $user->passenger) {
                $isClient = $tripClient->client_id === $user->passenger->id;
            }

            // Check if user is the trip driver
            if ($user->driver) {
                $isDriver = $tripClient->trip->driver_id === $user->driver->id;
            }

            if (!$isClient && !$isDriver) {
                throw new Exception('Unauthorized to delete this trip client', 403);
            }

            $trip = Trip::with('detailable', 'driver.user.wallet')->findOrFail($tripClient->trip_id);

            if (in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP]) && $tripClient->client_type === Passenger::class) {
                if ($user->driver && $user->wallet->balance < $tripClient->total_fees) {
                    throw new Exception('Insufficient wallet balance for refund');
                }

                $totalFees = $tripClient->total_fees;
                $driver = $trip->driver;
                $passenger = $tripClient->client;

                // Refund to client wallet
                $passenger->user->wallet->increment('balance', $totalFees);
                $driver->user->wallet->decrement('balance', $totalFees);

                // Create transaction record
                $passengerTransaction = Transaction::create([
                    'wallet_id' => $passenger->user->wallet->id,
                    'trip_id' => $trip->id,
                    'type' => TransactionType::REFUND,
                    'amount' => abs($totalFees),
                ]);

                $driverTransaction = Transaction::create([
                    'wallet_id' => $driver->user->wallet->id,
                    'trip_id' => $trip->id,
                    'type' => TransactionType::REFUND,
                    'amount' => -abs($totalFees)
                ]);

                // Send notifications
            $passenger->user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_REFUND,
                data: ['amount' => $passengerTransaction->amount, 'balance' => $passenger->user->wallet->balance]
            ));

            $driver->user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_REFUND,
                data: ['amount' => $driverTransaction->amount, 'balance' => $driver->user->wallet->balance]
            ));
                
            }

        
            $tripClient->delete();

            if ($trip->clients()->count() === 0) {
                $trip->update(['status' => TripStatus::CANCELED]);

                $notification = new NewMessageNotification(
                            key: NotificationMessages::TRIP_CANCELLED,
                            data: ['trip_id' => $trip->id]
                    );

                if ($isDriver) {
                    if ($tripClient->client_type === Passenger::class) {
                        $tripClient->client->user->notify($notification);
                    }
                } elseif ($isClient) {
                    $trip->driver->user->notify($notification);
                }
            }

            DB::commit();

            return $this->successResponse();

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}