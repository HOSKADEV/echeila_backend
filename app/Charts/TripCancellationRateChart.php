<?php

namespace App\Charts;

use App\Constants\TripStatus;
use App\Constants\UserType;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\Trip;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TripCancellationRateChart
{
    protected $chart;
    private bool $empty = false;

    private const COLOR_MAP = [
        'passenger' => '#fd7e14',
        'driver'    => '#dc3545',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(string $period = 'all'): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $base = Trip::query()->where('status', TripStatus::CANCELED);

        $passengerCancellations = $this->applyPeriodFilter(
            (clone $base)->where('canceled_by_type', UserType::PASSENGER),
            $period
        )->count();

        $driverCancellations = $this->applyPeriodFilter(
            (clone $base)->where('canceled_by_type', UserType::DRIVER),
            $period
        )->count();

        $this->empty = ($passengerCancellations + $driverCancellations) === 0;

        return $this->chart->donutChart()
            ->addData([$passengerCancellations, $driverCancellations])
            ->setLabels([__('dashboard.passengers'), __('dashboard.drivers')])
            ->setColors([self::COLOR_MAP['passenger'], self::COLOR_MAP['driver']])
            ->setDataLabels(true);
    }

    public function isEmpty(): bool
    {
        return $this->empty;
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
