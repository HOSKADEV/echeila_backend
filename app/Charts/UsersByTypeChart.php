<?php

namespace App\Charts;

use App\Constants\UserType;
use App\Models\Driver;
use App\Models\Federation;
use App\Models\Passenger;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class UsersByTypeChart
{
    protected $chart;

    private const COLOR_MAP = [
        'info'    => '#17a2b8',
        'warning' => '#ffc107',
        'purple'  => '#6f42c1',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $counts = [
            UserType::PASSENGER  => Passenger::count(),
            UserType::DRIVER     => Driver::count(),
            UserType::FEDERATION => Federation::count(),
        ];

        $labels = [];
        $colors = [];
        $data   = [];

        foreach ($counts as $type => $count) {
            $data[]   = $count;
            $labels[] = UserType::get_name($type);
            $bsColor  = UserType::get_color($type);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        return $this->chart->pieChart()
            ->addData($data)
            ->setLabels($labels)
            ->setColors($colors);
    }
}
