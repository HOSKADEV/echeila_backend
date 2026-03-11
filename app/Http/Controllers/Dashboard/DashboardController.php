<?php

namespace App\Http\Controllers\Dashboard;

use App\Charts\DriversByStatusChart;
use App\Charts\RevenuePerMonthChart;
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
use App\Models\Admin;
use App\Models\AdminAction;
use App\Models\Driver;
use App\Models\LostAndFound;
use App\Models\Passenger;
use App\Models\Transaction;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index(
        TripsPerMonthChart   $tripsPerMonthChart,
        TripsByStatusChart   $tripsByStatusChart,
        TripsByTypeChart     $tripsByTypeChart,
        RevenuePerMonthChart $revenuePerMonthChart,
        DriversByStatusChart $driversByStatusChart,
        UsersByTypeChart     $usersByTypeChart,
        UsersPerMonthChart   $usersPerMonthChart
    ) {
        // ── Stat cards ────────────────────────────────────────────────────────
        $totalPassengers  = Passenger::count();
        $approvedDrivers = Driver::where('status', DriverStatus::APPROVED)->count();
        $completedTrips  = Trip::where('status', TripStatus::COMPLETED)->count();
        $returnedItems   = LostAndFound::where('status', LostAndFoundStatus::RETURNED)->count();
        $adminActions    = AdminAction::count();
        $pendingDrivers  = Driver::where('status', DriverStatus::PENDING)->count();
        $activeTrips     = Trip::whereNotIn('status', [TripStatus::COMPLETED, TripStatus::CANCELED])->count();
        $foundItems      = LostAndFound::where('status', LostAndFoundStatus::FOUND)->count();

        $totalRevenue =
            Transaction::whereIn('type', [TransactionType::DEPOSIT, TransactionType::SUBSCRIBTION, TransactionType::WITHDRAW])
                ->sum('amount');

        // ── Charts ─────────────────────────────────────────────────────────────
        $tripsPerMonth   = $tripsPerMonthChart->build();
        $tripsByStatus   = $tripsByStatusChart->build();
        $tripsByType     = $tripsByTypeChart->build();
        $revenuePerMonth = $revenuePerMonthChart->build();
        $driversByStatus = $driversByStatusChart->build();
        $usersByType     = $usersByTypeChart->build();
        $usersPerMonth   = $usersPerMonthChart->build();

        return view('dashboard.index', compact(
            'totalPassengers',
            'approvedDrivers',
            'completedTrips',
            'returnedItems',
            'adminActions',
            'pendingDrivers',
            'activeTrips',
            'totalRevenue',
            'foundItems',
            'tripsPerMonth',
            'tripsByStatus',
            'tripsByType',
            'revenuePerMonth',
            'driversByStatus',
            'usersByType',
            'usersPerMonth'
        ));
    }
}
