<?php

namespace App\Http\Controllers\Dashboard;

use App\Charts\DriversByStatusChart;
use App\Charts\RevenuePerMonthChart;
use App\Charts\TripCancellationRateChart;
use App\Charts\TripsByStatusChart;
use App\Charts\TripsByTypeChart;
use App\Charts\TripsPerMonthChart;
use App\Charts\UsersPerMonthChart;
use App\Charts\UsersByTypeChart;
use App\Constants\DriverStatus;
use App\Constants\LostAndFoundStatus;
use App\Constants\TransactionType;
use App\Constants\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use App\Models\Driver;
use App\Models\Federation;
use App\Models\LostAndFound;
use App\Models\Passenger;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\TripReview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(
        Request                  $request,
        TripsPerMonthChart       $tripsPerMonthChart,
        TripsByStatusChart       $tripsByStatusChart,
        TripsByTypeChart         $tripsByTypeChart,
        RevenuePerMonthChart     $revenuePerMonthChart,
        DriversByStatusChart     $driversByStatusChart,
        UsersByTypeChart         $usersByTypeChart,
        UsersPerMonthChart       $usersPerMonthChart,
        TripCancellationRateChart $tripCancellationRateChart
    ) {
        $period = in_array($request->input('period'), ['today', 'week', 'month', 'all'])
            ? $request->input('period')
            : 'all';

        // ── All-time stat cards (current state, not filterable) ───────────────
        $totalUsers      = Passenger::count() + Driver::count() + Federation::count();
        $totalTrips      = Trip::count();
        $approvedDrivers = Driver::where('status', DriverStatus::APPROVED)->count();
        $pendingDrivers  = Driver::where('status', DriverStatus::PENDING)->count();
        $activeTrips     = Trip::whereNotIn('status', [TripStatus::COMPLETED, TripStatus::CANCELED])->count();
        $foundItems      = LostAndFound::where('status', LostAndFoundStatus::FOUND)->count();

        // ── Period-filtered stat cards ────────────────────────────────────────
        $totalPassengers = $this->applyPeriodFilter(Passenger::query(), $period)->count();
        $completedTrips  = $this->applyPeriodFilter(Trip::query()->where('status', TripStatus::COMPLETED), $period)->count();
        $returnedItems   = $this->applyPeriodFilter(LostAndFound::query()->where('status', LostAndFoundStatus::RETURNED), $period)->count();

        $totalRevenue = $this->applyPeriodFilter(
            Transaction::whereIn('type', [TransactionType::DEPOSIT, TransactionType::SUBSCRIBTION, TransactionType::WITHDRAW]),
            $period
        )->sum('amount');

        $newUsers = $this->applyPeriodFilter(Passenger::query(), $period)->count()
                  + $this->applyPeriodFilter(Driver::query(), $period)->count()
                  + $this->applyPeriodFilter(Federation::query(), $period)->count();

        $newTrips       = $this->applyPeriodFilter(Trip::query(), $period)->count();
        $cancelledTrips = $this->applyPeriodFilter(Trip::query()->where('status', TripStatus::CANCELED), $period)->count();

        $avgPassengerRating = round((float) ($this->applyPeriodFilter(
            TripReview::where('reviewee_type', Passenger::class), $period
        )->avg('rating') ?? 0), 1);

        $avgDriverRating = round((float) ($this->applyPeriodFilter(
            TripReview::where('reviewee_type', Driver::class), $period
        )->avg('rating') ?? 0), 1);

        // ── Charts ─────────────────────────────────────────────────────────────
        $tripsPerMonth        = $tripsPerMonthChart->build();
        $tripsPerMonthEmpty   = $tripsPerMonthChart->isEmpty();

        $tripsByStatus        = $tripsByStatusChart->build($period);
        $tripsByStatusEmpty   = $tripsByStatusChart->isEmpty();

        $tripsByType          = $tripsByTypeChart->build($period);
        $tripsByTypeEmpty     = $tripsByTypeChart->isEmpty();

        $revenuePerMonth      = $revenuePerMonthChart->build();
        $revenuePerMonthEmpty = $revenuePerMonthChart->isEmpty();

        $driversByStatus      = $driversByStatusChart->build($period);
        $driversByStatusEmpty = $driversByStatusChart->isEmpty();

        $usersByType          = $usersByTypeChart->build($period);
        $usersByTypeEmpty     = $usersByTypeChart->isEmpty();

        $usersPerMonth        = $usersPerMonthChart->build();
        $usersPerMonthEmpty   = $usersPerMonthChart->isEmpty();

        $tripCancellationRate      = $tripCancellationRateChart->build($period);
        $tripCancellationRateEmpty = $tripCancellationRateChart->isEmpty();

        return view('dashboard.index', compact(
            'period',
            // all-time
            'totalUsers',
            'totalTrips',
            'approvedDrivers',
            'pendingDrivers',
            'activeTrips',
            'foundItems',
            // period-filtered stats
            'totalPassengers',
            'completedTrips',
            'returnedItems',
            'totalRevenue',
            'newUsers',
            'newTrips',
            'cancelledTrips',
            'avgPassengerRating',
            'avgDriverRating',
            // charts + isEmpty flags
            'tripsPerMonth',        'tripsPerMonthEmpty',
            'tripsByStatus',        'tripsByStatusEmpty',
            'tripsByType',          'tripsByTypeEmpty',
            'revenuePerMonth',      'revenuePerMonthEmpty',
            'driversByStatus',      'driversByStatusEmpty',
            'usersByType',          'usersByTypeEmpty',
            'usersPerMonth',        'usersPerMonthEmpty',
            'tripCancellationRate', 'tripCancellationRateEmpty'
        ));
    }

    private function applyPeriodFilter($query, string $period)
    {
        return match ($period) {
            'today' => $query->whereDate('created_at', today()),
            'week'  => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]),
            default => $query,
        };
    }
}
