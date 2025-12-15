<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Constants\NotificationMessages;
use App\Datatables\DriverDatatable;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Federation;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\Permissions;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    use LogsAdminActions;

    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_INDEX)) {
            return redirect()->route('unauthorized');
        }

        // Calculate statistics
        $stats = [
            'total' => User::drivers()->count(),
            'active' => User::drivers()->where('status', 'active')->count(),
            'banned' => User::drivers()->where('status', 'banned')->count(),
            'new' => User::drivers()->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $drivers = new DriverDatatable;
        if ($request->wantsJson()) {
            return $drivers->datatables($request);
        }

        return view('dashboard.driver.list')->with([
            'columns' => $drivers::columns(),
            'stats' => $stats,
        ]);
    }

    public function show($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_SHOW)) {
            return redirect()->route('unauthorized');
        }
        $driver = Driver::with([
            'user.wallet.transactions',
            'services',
            'subscription',
            'trips',
            'reviewsReceived',
            'reviewsGiven',
        ])->where('user_id', $id)->first();

        $stats = [
            'trips_count' => $driver->trips()->count(),
            'reviews_count' => $driver->reviewsReceived()->count(),
            'avg_rating' => $driver->reviewsReceived()->avg('rating') ?? 0,
            'transactions_count' => $driver->user->wallet->transactions()->count(),
            'services_count' => $driver->services()->count(),
            'total_earned' => $driver->trips()->join('trip_clients', 'trips.id', '=', 'trip_clients.trip_id')
                ->sum('trip_clients.total_fees'),
        ];

        $transactions = $driver->user->wallet->transactions()->latest()->paginate(15);
        $recentTrips = $driver->trips()->latest()->paginate(10);
        $reviews = $driver->reviewsReceived()->with(['reviewer'])->latest()->paginate(5);

        return view('dashboard.driver.show', compact('driver', 'stats', 'transactions', 'recentTrips', 'reviews'));
    }

    public function updateStatus(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_CHANGE_STATUS)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|string|in:approved,denied',
            'confirmed' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($data['id']);
            $oldStatus = $user->driver->status;

            $user->driver->update(['status' => $data['status']]);

            // Log admin action
            $this->logChangeDriverStatus($user->driver, $oldStatus, $data['status'], $request->input('note'));

            // Send notification
            $notificationKey = $data['status'] === 'approved'
              ? NotificationMessages::DRIVER_APPROVED
              : NotificationMessages::DRIVER_DENIED;

            $user->notify(new NewMessageNotification(
                key: $notificationKey,
                data: ['status' => $data['status']]
            ));

            DB::commit();

            $statusMessage = $data['status'] === 'approved'
              ? __('driver.approved_successfully')
              : __('driver.denied_successfully');

            return redirect()->back()->with('success', $statusMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function purchaseSubscription(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_PURCHASE_SUBSCRIPTION)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'id' => 'required|exists:users,id',
            'months' => 'required|integer|min:1',
        ]);

        $monthlyFee = Setting::getValue('subscription_month_price') ?? 0;

        try {
            DB::beginTransaction();
            $user = User::findOrFail($data['id']);
            $driver = $user->driver;

            if (! $driver) {
                throw new \Exception('User is not a driver');
            }

            $months = (int) $data['months'];
            $subscription = $driver->subscription;

            $subscriptionData = [
                'old_status' => $subscription ? 'active' : 'none',
                'amount' => $monthlyFee * $months,
            ];

            $subscriptionOldDate = $subscription?->end_date?->toDateString();

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
            $subscriptionData['subscription_id'] = $subscription->id;
            $subscriptionData['old_date'] = $subscriptionOldDate;
            $subscriptionData['new_date'] = $subscription->end_date->toDateString();

            // Log admin action
            $this->logPurchaseSubscription($driver, $subscriptionData, $request->input('note'));

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_SUBSCRIPTION,
            ));

            DB::commit();

            return redirect()->back()->with('success', __('app.subscription_purchased_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addToFederation(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_CHANGE_STATUS)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'federation_id' => 'required|exists:federations,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        try {
            DB::beginTransaction();

            $federation = Federation::findOrFail($data['federation_id']);
            $driver = Driver::findOrFail($data['driver_id']);

            // Check if driver already belongs to a federation
            if ($driver->federation_id) {
                throw new \Exception(__('federation.driver_already_has_federation'));
            }

            // Add driver to federation
            $driver->update(['federation_id' => $federation->id]);

            // Send notification to driver
            /* $driver->user->notify(new NewMessageNotification(
              key: NotificationMessages::DRIVER_ADDED_TO_FEDERATION,
              data: ['federation_name' => $federation->name]
            )); */

            DB::commit();

            return redirect()->back()->with('success', __('federation.driver_added_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function removeFromFederation(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::DRIVER_CHANGE_STATUS)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'federation_id' => 'required|exists:federations,id',
            'driver_id' => 'required|exists:drivers,id',
            'confirmed' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            $federation = Federation::findOrFail($data['federation_id']);
            $driver = Driver::findOrFail($data['driver_id']);

            // Check if driver belongs to this federation
            if ($driver->federation_id != $federation->id) {
                throw new \Exception(__('federation.driver_not_in_federation'));
            }

            // Remove driver from federation
            $driver->update(['federation_id' => null]);

            // Send notification to driver
            /* $driver->user->notify(new NewMessageNotification(
              key: NotificationMessages::DRIVER_REMOVED_FROM_FEDERATION,
              data: ['federation_name' => $federation->name]
            )); */

            DB::commit();

            return redirect()->back()->with('success', __('federation.driver_removed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
