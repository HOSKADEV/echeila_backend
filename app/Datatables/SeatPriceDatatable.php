<?php

namespace App\Datatables;

use App\Models\SeatPrice;
use App\Support\Enum\Permissions;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SeatPriceDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'route',
            'default_seat_price',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->edit(route('seat-prices.edit', $model->id), Auth::user()->hasPermissionTo(Permissions::SEAT_PRICE_UPDATE))
                        ->delete($model->id, Auth::user()->hasPermissionTo(Permissions::SEAT_PRICE_DELETE))
                        ->makeLabelledIcons();
                })

                ->addColumn('route', function ($model) {
                    return $this->bold($model->startingWilaya->translate('name', app()->getLocale())) . (session('locale') == 'ar' ? ' ← ' : ' → ') . $this->bold($model->arrivalWilaya->translate('name', app()->getLocale()));
      })
                /* ->addColumn('starting_wilaya', function ($model) {
                    return $this->bold($model->startingWilaya->translate('name', app()->getLocale()));
                })
                ->addColumn('arrival_wilaya', function ($model) {
                    return $this->bold($model->arrivalWilaya->translate('name', app()->getLocale()));
                }) */
                ->addColumn('default_seat_price', function ($model) {
                    return $this->money($model->default_seat_price);
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = SeatPrice::query();


        if($request->starting_wilaya_filter){
            $query->where('starting_wilaya_id', $request->starting_wilaya_filter);
        }

        if($request->arrival_wilaya_filter){
            $query->where('arrival_wilaya_id', $request->arrival_wilaya_filter);
        }

        return $query->get();
    }
}
