<?php

namespace App\Charts;

use App\Models\Driver;
use App\Models\Federation;
use App\Models\Passenger;
use Illuminate\Support\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class UsersPerMonthChart
{
    protected $chart;
    private bool $empty = false;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $passengers = $months->map(
            fn ($m) => Passenger::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count()
        )->values()->toArray();

        $drivers = $months->map(
            fn ($m) => Driver::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count()
        )->values()->toArray();

        $federations = $months->map(
            fn ($m) => Federation::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count()
        )->values()->toArray();

        $labels = $months->map(fn ($m) => $m->format('M Y'))->toArray();

        $this->empty = (array_sum($passengers) + array_sum($drivers) + array_sum($federations)) === 0;

        return $this->chart->lineChart()
            ->addData($passengers, __('dashboard.passengers'))
            ->addData($drivers, __('dashboard.drivers'))
            ->addData($federations, __('dashboard.federations'))
            ->setXAxis($labels)
            ->setColors(['#17a2b8', '#ffc107', '#6f42c1'])
            ->setGrid();
    }

    public function isEmpty(): bool
    {
        return $this->empty;
    }
}
