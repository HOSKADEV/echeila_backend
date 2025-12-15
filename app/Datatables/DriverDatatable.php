<?php

namespace App\Datatables;

use Exception;
use App\Models\User;
use App\Models\Setting;
use App\Constants\UserStatus;
use App\Constants\DriverStatus;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class DriverDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'driver',
            'phone',
            'subscription',
            'driver_status',
            'wallet_balance',
            'user_status',

            'action',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('action', function ($model) {
                    $walletBalance = $model->wallet?->balance ?? 0;
                    $subscriptionEndDate = $model->driver->subscription?->end_date ?? null;
                    $monthlyFee = Setting::getValue('subscription_month_price') ?? 0;

                    return $this
                        ->show(route('drivers.show', $model->id), Auth::user()->hasPermissionTo(Permissions::DRIVER_SHOW))
                        ->modalButton('user-status-activate-modal', __('app.activate'), 'bx bx-lock-open', ['id' => $model->id], $model->status === UserStatus::BANNED && Auth::user()->hasPermissionTo(Permissions::DRIVER_CHANGE_USER_STATUS), UserStatus::get_color(UserStatus::ACTIVE))
                        ->modalButton('user-status-suspend-modal', __('app.suspend'), 'bx bx-lock', ['id' => $model->id], $model->status === UserStatus::ACTIVE && Auth::user()->hasPermissionTo(Permissions::DRIVER_CHANGE_USER_STATUS), UserStatus::get_color(UserStatus::BANNED))
                        ->modalButton('driver-status-approve-modal', __('app.approve'), 'bx bx-check-circle', ['id' => $model->id], $model->driver->status !== DriverStatus::APPROVED && Auth::user()->hasPermissionTo(Permissions::DRIVER_CHANGE_STATUS), DriverStatus::get_color(DriverStatus::APPROVED))
                        ->modalButton('driver-status-deny-modal', __('app.deny'), 'bx bx-x-circle', ['id' => $model->id], $model->driver->status !== DriverStatus::DENIED && Auth::user()->hasPermissionTo(Permissions::DRIVER_CHANGE_STATUS), DriverStatus::get_color(DriverStatus::DENIED))
                        ->modalButton('charge-wallet-modal', __('app.charge_wallet'), 'bx bx-wallet', ['id' => $model->id, 'wallet-balance' => $walletBalance], Auth::user()->hasPermissionTo(Permissions::DRIVER_CHARGE_WALLET), 'blue')
                        ->modalButton('withdraw-sum-modal', __('app.withdraw'), 'bx bx-money', ['id' => $model->id, 'wallet-balance' => $walletBalance], Auth::user()->hasPermissionTo(Permissions::DRIVER_WITHDRAW_SUM), 'teal')
                        ->modalButton('purchase-subscription-modal', __('app.purchase_subscription'), 'bx bx-calendar', ['id' => $model->id, 'subscription-end-date' => $subscriptionEndDate, 'monthly-fee' => $monthlyFee], Auth::user()->hasPermissionTo(Permissions::DRIVER_PURCHASE_SUBSCRIPTION), 'purple')
                        ->makeLabelledIcons();
                })
                ->addColumn('driver', function ($model) {
                    return $this->thumbnailTitleMeta($model->driver->avatar_url, $model->driver->fullname, $model->driver->federation?->name, route('drivers.show', $model->id));
                })
                ->addColumn('subscription', function ($model) {
                    return $model->driver->subscription
                    ? $this->statusBadge($this->date($model->driver->subscription->end_date), 'success', 'bx bx-calendar-check')
                    : $this->statusBadge(__('driver.subscription_expired'), 'danger', 'bx bx-calendar-x');

                })
                ->addColumn('phone', function ($model) {
                    return $model->phone;
                })
                ->addColumn('driver_status', function ($model) {
                    return $this->StatusBadge(DriverStatus::get_name($model->driver->status), DriverStatus::get_color($model->driver->status), 'bx bx-badge-check');
                })
                ->addColumn('wallet_balance', function ($model) {
                    return $this->money($model->wallet?->balance ?? 0);
                })
                ->addColumn('user_status', function ($model) {
                    return $this->badge(UserStatus::get_name($model->status), UserStatus::get_color($model->status));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this).' Error '.$e->getMessage());
        }
    }

    public function query($request)
    {
        $query = User::drivers();

        if ($request->user_status_filter) {
            $query->where('status', $request->user_status_filter);
        }
        if ($request->federation_filter) {
            $query->where('federation_id', $request->federation_filter);
        }
        if ($request->driver_status_filter) {
            $query->whereHas('driver', function ($q) use ($request) {
                $q->where('status', $request->driver_status_filter);
            });
        }

        return $query->with(['driver.federation', 'driver.subscription', 'wallet'])->get();
    }
}
