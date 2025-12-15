<?php

namespace App\Datatables;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Constants\WaterType;
use App\Constants\TripStatus;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class WaterTransportDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'identifier',
            'driver',
            'passenger',
            'water_type',
            'delivery_point',
            'status',
            'created_at',
            'actions',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('actions', function ($model) {
                    return $this
                        ->show(route('trips.show', $model->id), Auth::user()->hasPermissionTo(Permissions::WATER_TRANSPORT_INDEX))
                        ->makeLabelledIcons();
                })
                ->addColumn('identifier', function ($model) {
                    return '#' . $model->identifier;
                })
                ->addColumn('driver', function ($model) {
                    return $model->driver && $model->driver->user
                        ? $this->thumbnailTitleMeta(
                            $model->driver->avatar_url,
                            $model->driver->fullname,
                            $model->driver->phone ?? ''
                        )
                        : '-';
                })
                ->addColumn('passenger', function ($model) {
                    $passenger = $model->clients()->first()?->client;
                    return $passenger
                        ? $this->thumbnailTitleMeta(
                            $passenger->avatar_url ?? asset('assets/img/default-avatar.png'),
                            $passenger->fullname ?? '-',
                            $passenger->phone ?? ''
                        )
                        : '-';
                })
                ->addColumn('water_type', function ($model) {
                    $details = $model->detailable;
                    return $details && $details->water_type 
                        ? $this->badge(WaterType::get_name($details->water_type), WaterType::get_color($details->water_type))
                        : '-';
                })
                ->addColumn('delivery_point', function ($model) {
                    $details = $model->detailable;
                    if ($details) {
                        $point = $this->link($details->deliveryPoint->url, $details->deliveryPoint->name);
                        $time = $details->delivery_time->format('Y-m-d H:i');
                        return '<small><strong>' . $point . '</strong><br>' . $time . '</small>';
                    }
                    return '-';
                })
                ->addColumn('status', function ($model) {
                    $statusName = TripStatus::get_name($model->status);
                    $color = TripStatus::get_color($model->status);
                    return $this->badge($statusName, $color);
                })
                ->addColumn('created_at', function ($model) {
                    return $model->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Trip::with(['driver.user', 'clients.client', 'detailable'])
            ->where('type', TripType::WATER_TRANSPORT);

        // Filter by status
        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        // Filter by driver
        if ($request->driver_filter) {
            $query->where('driver_id', $request->driver_filter);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by water type - Use whereHasMorph
        if ($request->water_type_filter) {
            $query->whereHasMorph('detailable', [\App\Models\WaterTransportDetail::class], function ($q) use ($request) {
                $q->where('water_type', $request->water_type_filter);
            });
        }

        return $query->latest()->get();
    }
}
