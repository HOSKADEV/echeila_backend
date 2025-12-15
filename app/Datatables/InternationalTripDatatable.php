<?php

namespace App\Datatables;

use Exception;
use App\Models\Trip;
use App\Constants\TripType;
use App\Constants\Direction;
use App\Constants\TripStatus;
use App\Support\Enum\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\DataTableActionsTrait;

class InternationalTripDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'identifier',
            'driver',
            'route',
            'starting_arrival',
            'seats_info',
            'status',
            'created_at',
            'actions',
        ];
    }

    public function datatables($request, $tripType)
    {
        try {
            // Determine which permission to check based on trip type
            $permission = $tripType == TripType::MRT_TRIP 
                ? Permissions::MRT_TRIP_SHOW 
                : Permissions::ESP_TRIP_SHOW;
            
            return datatables($this->query($request, $tripType))
                ->addColumn('actions', function ($model) use ($permission) {
                    return $this
                        ->show(route('trips.show', $model->id), Auth::user()->hasPermissionTo($permission))
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
                ->addColumn('route', function ($model) use ($tripType) {
                    $details = $model->detailable;
                    if ($details) {
                        if($tripType == TripType::MRT_TRIP) {
                            return $details->direction == Direction::FROM ? __('trip.mrt_from') : __('trip.mrt_to');
                        }else{
                          return $details->direction == Direction::FROM ? __('trip.esp_from') : __('trip.esp_to');
                        }        
                    }
                    return '-';
                })

                ->addColumn('starting_arrival', function ($model) {
                    $details = $model->detailable;
                    if ($details) {
                        $point = $details->starting_place;
                        $starting_time = $details->starting_time->format('Y-m-d H:i');
                        $arrival_time = $details->arrival_time->format('Y-m-d H:i');
                        return '<small>' .
                               '<strong><i class="bx bx-map"></i> ' . $point . '</strong><br>' .
                               '<span class="text-muted"><i class="bx bx-time"></i> ' . $starting_time . '</span><br>' .
                               '<span class="text-success"><i class="bx bx-check-circle"></i> ' . $arrival_time . '</span>' .
                               '</small>';
                    }
                    return '-';
                })
                ->addColumn('seats_info', function ($model) {
                    $details = $model->detailable;
                    if ($details) {
                        $totalSeats = $details->total_seats ?? 0;
                        $availableSeats = $model->available_seats ?? 0;
                        $seatPrice = $details->seat_price ?? 0;
                        $currency = __('app.DZD');
                        return "<small>" .
                               "<i class='bx bx-chair'></i> {$totalSeats}<br>" . 
                               "<i class='bx bx-money'></i> {$seatPrice} {$currency} <br>" .
                               "<i class='bx bx-check text-success'></i> <span class='text-success'>{$availableSeats}</span>" .
                               "</small>";
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

    public function query($request, $type = TripType::MRT_TRIP)
    {
        $query = Trip::with(['driver.user', 'detailable'])
            ->where('type', $type);

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

        // Filter by wilaya - Use whereHasMorph
        if ($request->direction_filter) {
            $query->whereHasMorph('detailable', [\App\Models\InternationalTripDetail::class], function ($q) use ($request) {
                $q->where('direction', $request->direction_filter);
            });
        }


        return $query->latest()->get();
    }
}
