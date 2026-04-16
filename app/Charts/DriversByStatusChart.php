<?php

namespace App\Charts;

use App\Models\Driver;
use App\Constants\DriverStatus;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Database\Eloquent\Builder;

class DriversByStatusChart
{
    protected $chart;
    private bool $empty = false;

    private const COLOR_MAP = [
        'info'    => '#17a2b8',
        'green'   => '#28a745',
        'warning' => '#fd7e14',
    ];

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    private function applyPeriodFilter(Builder $query, string $period): Builder
    {
        return match ($period) {
            'today' => $query->whereDate('created_at', today()),
            'week'  => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            default => $query,
        };
    }

    public function build(string $period = 'all'): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $statuses = DriverStatus::all();
        $data     = [];
        $labels   = [];
        $colors   = [];

        foreach ($statuses as $status) {
            $data[]   = $this->applyPeriodFilter(Driver::where('status', $status), $period)->count();
            $labels[] = DriverStatus::get_name($status);
            $bsColor  = DriverStatus::get_color($status);
            $colors[] = self::COLOR_MAP[$bsColor] ?? '#6c757d';
        }

        $this->empty = array_sum($data) === 0;

        return $this->chart->donutChart()
            ->addData($data)
            ->setLabels($labels)
            ->setColors($colors)
            ->setDataLabels(true);
    }

    public function isEmpty(): bool
    {
        return $this->empty;
    }
}
