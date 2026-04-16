<?php

namespace App\Charts;

use App\Models\Transaction;
use App\Constants\TransactionType;
use Illuminate\Support\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class RevenuePerMonthChart
{
    protected $chart;
    private bool $empty = false;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $income = $months->map(
            fn ($month) => (float) Transaction::whereIn('type', [
                    TransactionType::DEPOSIT,
                    TransactionType::SUBSCRIBTION,
                ])
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount')
        )->values()->toArray();

        $withdrawals = $months->map(
            fn ($month) => (float) Transaction::where('type', TransactionType::WITHDRAW)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount')
        )->values()->toArray();

        $labels = $months->map(fn ($month) => $month->format('M Y'))->toArray();

        $this->empty = (array_sum($income) + array_sum($withdrawals)) === 0.0;

        return $this->chart->areaChart()
            ->addData($income, __('dashboard.income'))
            ->addData($withdrawals, __('dashboard.withdrawals'))
            ->setXAxis($labels)
            ->setColors(['#28a745', '#dc3545'])
            ->setGrid();
    }

    public function isEmpty(): bool
    {
        return $this->empty;
    }
}
