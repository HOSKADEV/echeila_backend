<?php

namespace App\Charts;

use App\Models\Trip;
use App\Constants\TripType;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TripsByTypeChart
{
    protected $chart;

    private const COLOR_MAP = [
        'blue'   => '#3B7DDD',
        'purple' => '#6f42c1',
        'red'    => '#dc3545',
        'orange' => '#fd7e14',
        'yellow' => '#ffc107',
        'cyan'   => '#0dcaf0',
        'teal'   => '#20c997',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $types  = TripType::all();
        $counts = [];
        $labels = [];
        $colors = [];

        foreach ($types as $type) {
            $counts[] = Trip::where('type', $type)->count();
            $labels[] = TripType::get_name($type);
            $bsColor  = TripType::get_color($type);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        $chart = $this->chart->barChart()
            ->setXAxis([__('dashboard.trips')])
            ->setColors($colors);

        foreach ($counts as $i => $count) {
            $chart->addData([$count], $labels[$i]);
        }

        return $chart;
    }
}
