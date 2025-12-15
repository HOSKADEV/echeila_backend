<?php

namespace App\Datatables;

use App\Constants\UserStatus;
use App\Models\User;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PassengerDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'passenger',
            'phone',
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
                    return $this
                        ->show(route('passengers.show', $model->id), Auth::user()->hasPermissionTo(Permissions::PASSENGER_SHOW))
                        ->modalButton('user-status-activate-modal', __('app.activate'), 'bx bx-lock-open', ['id' => $model->id], $model->status === UserStatus::BANNED && Auth::user()->hasPermissionTo(Permissions::PASSENGER_CHANGE_USER_STATUS), UserStatus::get_color(UserStatus::ACTIVE))
                        ->modalButton('user-status-suspend-modal', __('app.suspend'), 'bx bx-lock', ['id' => $model->id], $model->status === UserStatus::ACTIVE && Auth::user()->hasPermissionTo(Permissions::PASSENGER_CHANGE_USER_STATUS), UserStatus::get_color(UserStatus::BANNED))
                        ->modalButton('charge-wallet-modal', __('app.charge_wallet'), 'bx bx-wallet', ['id' => $model->id, 'wallet-balance' => $walletBalance], Auth::user()->hasPermissionTo(Permissions::PASSENGER_CHARGE_WALLET), 'blue')
                        ->modalButton('withdraw-sum-modal', __('app.withdraw'), 'bx bx-money', ['id' => $model->id, 'wallet-balance' => $walletBalance], Auth::user()->hasPermissionTo(Permissions::PASSENGER_WITHDRAW_SUM), 'teal')
                        ->makeLabelledIcons();
                })
                ->addColumn('passenger', function ($model) {
                    return $this->thumbnailTitleMeta($model->passenger->avatar_url, $model->passenger->fullname, null, route('passengers.show', $model->id));
                })
                ->addColumn('phone', function ($model) {
                    return $model->phone;
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
        $query = User::passengers();

        if ($request->user_status_filter) {
            $query->where('status', $request->user_status_filter);
        }

        return $query->with(['passenger', 'wallet'])->get();
    }
}
