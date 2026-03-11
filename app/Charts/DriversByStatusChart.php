<?php

namespace App\Charts;

use App\Models\Driver;
use App\Constants\DriverStatus;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DriversByStatusChart
{
    protected $chart;

    private const COLOR_MAP = [
        'info'    => '#17a2b8',
        'green'   => '#28a745',
        'warning' => '#fd7e14',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $statuses = DriverStatus::all();
        $data     = [];
        $labels   = [];
        $colors   = [];

        foreach ($statuses as $status) {
            $data[]   = Driver::where('status', $status)->count();
            $labels[] = DriverStatus::get_name($status);
            $bsColor  = DriverStatus::get_color($status);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        return $this->chart->donutChart()
            ->addData($data)
            ->setLabels($labels)
            ->setColors($colors);
    }
}
