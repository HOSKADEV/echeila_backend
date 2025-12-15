<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Trip;
use App\Constants\TripType;
use Illuminate\Http\Request;
use App\Constants\TripStatus;
use App\Support\Enum\Permissions;
use App\Datatables\TripDatatable;
use App\Http\Controllers\Controller;
use App\Datatables\TaxiRideDatatable;
use App\Datatables\CarRescueDatatable;
use App\Datatables\PaidDrivingDatatable;
use App\Datatables\CargoTransportDatatable;
use App\Datatables\WaterTransportDatatable;
use App\Datatables\InternationalTripDatatable;

class TripController extends Controller
{
    public function index(Request $request, $type = 'all')
    {
        // Check permissions based on trip type
        $this->checkTripPermission($type);
        
        if ($request->wantsJson()) {
            return $this->getDatatable($request, $type);
        }

        $viewName = $this->getIndexViewName($type);

        return view($viewName)->with([
            'columns' => $this->getDatatableColumns($type),
        ]);
    }

    public function show($id)
    {
        
        $trip = Trip::with([
            'driver',
            'client.client',
            'reviews.reviewer',
            'reviews.reviewee',
            'transactions'
        ])->findOrFail($id);

        $this->checkTripShowPermission($trip->type);

        // Load type-specific relationships
        switch ($trip->type) {
            case TripType::TAXI_RIDE:
                $trip->load([
                    'detailable.startingPoint',
                    'detailable.arrivalPoint'
                ]);
                break;
            case TripType::CAR_RESCUE:
                $trip->load('detailable.breakdownPoint');
                break;
            case TripType::CARGO_TRANSPORT:
                $trip->load([
                    'cargos.cargo',
                    'detailable.deliveryPoint'
                ]);
                break;
            case TripType::WATER_TRANSPORT:
                $trip->load('detailable.deliveryPoint');
                break;
            case TripType::PAID_DRIVING:
                $trip->load([
                    'detailable.startingPoint',
                    'detailable.arrivalPoint'
                ]);
                break;
            case TripType::MRT_TRIP:
            case TripType::ESP_TRIP:
                $trip->load([
                    'clients.client',
                    'cargos.cargo',
                    'detailable'
                ]);
                break;
        }

        $viewName = $this->getShowViewName($trip->type);

        return view($viewName)->with([
            'trip' => $trip,
        ]);
    }

    private function getDatatable($request, $type)
    {
        return match($type) {
            'all' => (new TripDatatable())->datatables($request),
            'taxi_ride' => (new TaxiRideDatatable())->datatables($request),
            'car_rescue' => (new CarRescueDatatable())->datatables($request),
            'cargo_transport' => (new CargoTransportDatatable())->datatables($request),
            'water_transport' => (new WaterTransportDatatable())->datatables($request),
            'paid_driving' => (new PaidDrivingDatatable())->datatables($request),
            'mrt_trip' => (new InternationalTripDatatable())->datatables($request, TripType::MRT_TRIP),
            'esp_trip' => (new InternationalTripDatatable())->datatables($request, TripType::ESP_TRIP),
        };
    }

    private function getDatatableColumns($type)
    {
        return match($type) {
            'all' => TripDatatable::columns(),
            'taxi_ride' => TaxiRideDatatable::columns(),
            'car_rescue' => CarRescueDatatable::columns(),
            'cargo_transport' => CargoTransportDatatable::columns(),
            'water_transport' => WaterTransportDatatable::columns(),
            'paid_driving' => PaidDrivingDatatable::columns(),
            'mrt_trip' => InternationalTripDatatable::columns(),
            'esp_trip' => InternationalTripDatatable::columns(),
        };
    }

    private function getIndexViewName($type)
    {
        return match($type) {
            'all' => 'dashboard.trip.list',
            'taxi_ride' => 'dashboard.trip.taxi-ride',
            'car_rescue' => 'dashboard.trip.car-rescue',
            'cargo_transport' => 'dashboard.trip.cargo-transport',
            'water_transport' => 'dashboard.trip.water-transport',
            'paid_driving' => 'dashboard.trip.paid-driving',
            'mrt_trip' => 'dashboard.trip.mrt-trip',
            'esp_trip' => 'dashboard.trip.esp-trip',
        };
    }

    public function getShowViewName($type)
    {
        return match($type) {
            TripType::TAXI_RIDE => 'dashboard.trip.show-taxi-ride',
            TripType::CAR_RESCUE => 'dashboard.trip.show-car-rescue',
            TripType::CARGO_TRANSPORT => 'dashboard.trip.show-cargo-transport',
            TripType::WATER_TRANSPORT => 'dashboard.trip.show-water-transport',
            TripType::PAID_DRIVING => 'dashboard.trip.show-paid-driving',
            TripType::MRT_TRIP => 'dashboard.trip.show-international-trip',
            TripType::ESP_TRIP => 'dashboard.trip.show-international-trip',
            default => 'dashboard.trip.show',
        };
    }

    private function checkTripPermission($type)
    {
        $permission = match($type) {
            'all' => Permissions::ALL_TRIPS_INDEX,
            'taxi_ride' => Permissions::TAXI_RIDE_INDEX,
            'car_rescue' => Permissions::CAR_RESCUE_INDEX,
            'cargo_transport' => Permissions::CARGO_TRANSPORT_INDEX,
            'water_transport' => Permissions::WATER_TRANSPORT_INDEX,
            'paid_driving' => Permissions::PAID_DRIVING_INDEX,
            'mrt_trip' => Permissions::MRT_TRIP_INDEX,
            'esp_trip' => Permissions::ESP_TRIP_INDEX,
            default => Permissions::ALL_TRIPS_INDEX,
        };

        if (! auth()->user()->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized');
        }
    }

    public function checkTripShowPermission($type)
    {
        $permission = match($type) {
            TripType::TAXI_RIDE => Permissions::TAXI_RIDE_SHOW,
            TripType::CAR_RESCUE => Permissions::CAR_RESCUE_SHOW,
            TripType::CARGO_TRANSPORT => Permissions::CARGO_TRANSPORT_SHOW,
            TripType::WATER_TRANSPORT => Permissions::WATER_TRANSPORT_SHOW,
            TripType::PAID_DRIVING => Permissions::PAID_DRIVING_SHOW,
            TripType::MRT_TRIP => Permissions::MRT_TRIP_SHOW,
            TripType::ESP_TRIP => Permissions::ESP_TRIP_SHOW,
            default => Permissions::ALL_TRIPS_INDEX,
        };

        if (! auth()->user()->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized');
        }
    }
}