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
    private bool $empty = false;

    private const COLOR_MAP = [
        'info'    => '#17a2b8',
        'warning' => '#ffc107',
        'purple'  => '#6f42c1',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(string $period = 'all'): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $counts = [
            UserType::PASSENGER  => $this->applyPeriodFilter(Passenger::query(), $period)->count(),
            UserType::DRIVER     => $this->applyPeriodFilter(Driver::query(), $period)->count(),
            UserType::FEDERATION => $this->applyPeriodFilter(Federation::query(), $period)->count(),
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

        $this->empty = array_sum($data) === 0;

        return $this->chart->pieChart()
            ->addData($data)
            ->setLabels($labels)
            ->setColors($colors)
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
