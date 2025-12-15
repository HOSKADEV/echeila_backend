<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\DriverStatus;
use App\Traits\ApiResponseTrait;
use App\Constants\TransactionType;
use App\Http\Controllers\Controller;
use App\Constants\NotificationMessages;
use App\Http\Resources\SubscriptionResource;
use App\Notifications\NewMessageNotification;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function updateDriverStatus(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'status' => 'required|in:'.implode(',', DriverStatus::all()),
        ]);

        try {
            $driver = Driver::find($request->driver_id);
            $driver->update(['status' => $request->status]);

            // Send notification
            $driver->user->notify(new NewMessageNotification(
                $request->status == DriverStatus::APPROVED ?
                 NotificationMessages::DRIVER_APPROVED :
                   NotificationMessages::DRIVER_DENIED,
                ['status' => $request->status]
            ));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function chargeWallet(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $user = User::find($request->user_id);
            $wallet = $user->wallet;
            $wallet->increment('balance', $request->amount);

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::DEPOSIT,
                'amount' => abs($request->amount),
            ]);

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_DEPOSIT,
                data: ['amount' => $transaction->amount, 'balance' => $wallet->balance]
            ));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function withdrawSum(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $user = User::find($request->user_id);
            $wallet = $user->wallet;

            if ($wallet->balance < $request->amount) {
                throw new Exception('Insufficient balance');
            }

            $wallet->decrement('balance', $request->amount);

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::WITHDRAW,
                'amount' => -abs($request->amount),
            ]);

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_WITHDRAW,
                data: ['amount' => $transaction->amount, 'balance' => $wallet->balance]
            ));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function purchaseSubscription(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'months' => 'required|integer|min:1',
        ]);

        $monthlyFee = Setting::getValue('subscription_month_price') ?? 0;

        try {
            $user = User::find($request->user_id);
            $driver = $user->driver;
            if (! $driver) {
                throw new Exception('User is not a driver');
            }

            $months = (int) $request->months;
            $subscription = $driver->subscription;

            if ($subscription) {
                // Extend existing subscription
                $subscription->update(['end_date' => $subscription->end_date->copy()->addMonths($months)]);
            } else {
                // Create new subscription
                $subscription = $driver->subscriptions()->create([
                    'start_date' => now(),
                    'end_date' => now()->addMonths($months),
                ]);
            }

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_SUBSCRIPTION,
            ));

            return $this->successResponse(new SubscriptionResource($subscription));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
