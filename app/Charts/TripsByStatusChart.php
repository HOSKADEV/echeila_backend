<?php

namespace App\Charts;

use App\Models\Trip;
use App\Constants\TripStatus;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TripsByStatusChart
{
    protected $chart;

    // Bootstrap color name => ApexCharts hex
    private const COLOR_MAP = [
        'warning' => '#fd7e14',
        'info'    => '#17a2b8',
        'blue'    => '#3B7DDD',
        'danger'  => '#dc3545',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PolarAreaChart
    {
        $statuses = TripStatus::all();
        $counts   = [];
        $labels   = [];
        $colors   = [];

        foreach ($statuses as $status) {
            $counts[] = Trip::where('status', $status)->count();
            $labels[] = TripStatus::get_name($status);
            $bsColor  = TripStatus::get_color($status);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        return $this->chart->polarAreaChart()
            ->addData($counts)
            ->setLabels($labels)
            ->setColors($colors);
    }
}
