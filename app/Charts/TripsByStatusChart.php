<?php

namespace App\Charts;

use App\Models\Trip;
use App\Constants\TripStatus;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TripsByStatusChart
{
    protected $chart;
    private bool $empty = false;

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

    public function build(string $period = 'all'): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $statuses = TripStatus::all();
        $counts   = [];
        $labels   = [];
        $colors   = [];

        foreach ($statuses as $status) {
            $query    = Trip::where('status', $status);
            $counts[] = $this->applyPeriodFilter($query, $period)->count();
            $labels[] = TripStatus::get_name($status);
            $bsColor  = TripStatus::get_color($status);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        $this->empty = array_sum($counts) === 0;

        return $this->chart->pieChart()
            ->addData($counts)
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
