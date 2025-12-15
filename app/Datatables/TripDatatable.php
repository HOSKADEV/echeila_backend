<?php

namespace App\Datatables;

use Exception;
use App\Models\Trip;
use App\Constants\TripStatus;
use App\Constants\TripType;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class TripDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'identifier',
            'driver',
            'type',
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
                        ->show(route('trips.show', $model->id), Auth::user()->hasPermissionTo(Permissions::ALL_TRIPS_INDEX))
                        ->makeLabelledIcons();
                })
                ->addColumn('identifier', function ($model) {
                    return $model->identifier;
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
                ->addColumn('status', function ($model) {
                    $statusName = TripStatus::get_name($model->status);
                    $color = TripStatus::get_color($model->status);
                    return $this->badge($statusName, $color);
                })
                ->addColumn('type', function ($model) {
                    $typeName = TripType::get_name($model->type);
                    $color = TripType::get_color($model->type);
                    return $this->badge($typeName, $color);
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
        $query = Trip::with(['driver.user']);

        // Filter by trip type
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        // Filter by driver
        if ($request->driver_filter) {
            $query->where('driver_id', $request->driver_filter);
        }

        // Filter by passenger
        if ($request->passenger_filter) {
            $query->where('passenger_id', $request->passenger_filter);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest()->get();
    }
}