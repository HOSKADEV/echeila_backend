<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Trip;
use App\Models\Cargo;
use App\Models\TripCargo;
use App\Constants\TripType;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Constants\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\NotificationMessages;
use App\Http\Resources\TripCargoResource;
use App\Notifications\NewMessageNotification;
use App\Http\Requests\Api\TripCargo\StoreTripCargoRequest;

class TripCargoController extends Controller
{
    use ApiResponseTrait, ImageUpload;

    /**
     * Get trip cargos for a specific trip
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

            $tripCargos = $trip->cargos()
                ->with(['cargo.passenger.user'])
                ->paginate(10);

            return $this->successResponse(
                TripCargoResource::collection($tripCargos)
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Store a new trip cargo
     */
    public function store(StoreTripCargoRequest $request)
    {
        $validated = $this->validateRequest($request);
    
        try {
            $user = auth()->user();
            $trip = Trip::with('detailable', 'driver.user.wallet')->findOrFail($validated['trip_id']);
            $driver = $trip->driver;
    
            if (!in_array($trip->type, [TripType::MRT_TRIP, TripType::ESP_TRIP])) {
                throw new Exception('This trip in not an international trip');
            }
    
            if (!$user->passenger) {
                throw new Exception('User must have a passenger profile to add cargo');
            }
    
            // Check wallet balance
            if($validated['total_fees'] > $user->wallet->balance) {
                throw new Exception('Insufficient wallet balance');
            }
    
            DB::beginTransaction();
    
            // Deduct from user wallet and add to driver wallet
            $user->wallet->decrement('balance', $validated['total_fees']);
            $trip->driver->user->wallet->increment('balance', $validated['total_fees']);
    
            // Create transactions
            $passengerTransaction = Transaction::create([
                'wallet_id' => $user->wallet->id,
                'trip_id' => $validated['trip_id'],
                'type' => TransactionType::RESERVATION,
                'amount' => -abs($validated['total_fees']),
            ]);
    
            $driverTransaction = Transaction::create([
                'wallet_id' => $trip->driver->user->wallet->id,
                'trip_id' => $validated['trip_id'],
                'type' => TransactionType::RESERVATION,
                'amount' => abs($validated['total_fees']),
            ]);
    
            // Create cargo
            $cargo = Cargo::create([
                'passenger_id' => $user->passenger->id,
                'description' => $validated['cargo']['description'],
                'weight' => $validated['cargo']['weight'],
            ]);
    
            // Handle cargo images
            if ($request->hasFile('cargo.images')) {
                foreach ($request->file('cargo.images') as $image) {
                    $cargo->addMedia($image)
                        ->toMediaCollection(Cargo::IMAGES);
                }
            }
    
            // Create trip cargo
            $tripCargo = TripCargo::create([
                'trip_id' => $validated['trip_id'],
                'cargo_id' => $cargo->id,
                'total_fees' => $validated['total_fees']
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
    
            $tripCargo->load(['trip', 'cargo.passenger.user']);
    
            DB::commit();
    
            return $this->successResponse(
                new TripCargoResource($tripCargo)
            );
    
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a trip cargo
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $user = auth()->user();
            $tripCargo = TripCargo::findOrFail($id);
            $isCargoOwner = false;
            $isDriver = false;
    
            if ($user->passenger && $tripCargo->cargo) {
                $isCargoOwner = $tripCargo->cargo->passenger_id === $user->passenger->id;
            }
    
            if ($user->driver) {
                $isDriver = $tripCargo->trip->driver_id === $user->driver->id;
            }
    
            if (!$isCargoOwner && !$isDriver) {
                throw new Exception('Unauthorized to delete this trip cargo', 403);
            }
    
            $trip = Trip::with('detailable', 'driver.user.wallet')->findOrFail($tripCargo->trip_id);
    
            // Handle refund if cargo owner is deleting
            if ($isCargoOwner) {
                $totalFees = $tripCargo->total_fees;
                $passenger = $tripCargo->cargo->passenger;
                $driver = $trip->driver;
    
                // Refund to passenger wallet
                $passenger->user->wallet->increment('balance', $totalFees);
                $driver->user->wallet->decrement('balance', $totalFees);
    
                // Create transaction records
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
    
            $tripCargo->delete();
            DB::commit();
    
            return $this->successResponse();
    
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}