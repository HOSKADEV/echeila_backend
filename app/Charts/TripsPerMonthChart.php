<?php

namespace App\Charts;

use App\Models\Trip;
use Illuminate\Support\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TripsPerMonthChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $data = $months->map(
            fn ($month) => Trip::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count()
        )->values()->toArray();

        $labels = $months->map(fn ($month) => $month->format('M Y'))->toArray();

        return $this->chart->areaChart()
            ->addData($data, __('dashboard.trips'))
            ->setXAxis($labels);
    }
}
