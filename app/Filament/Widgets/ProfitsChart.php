<?php

namespace App\Filament\Widgets;

use App\Models\Cost;
use App\Models\ProductProfit;
use Filament\Widgets\ChartWidget;

class ProfitsChart extends ChartWidget
{
    protected ?string $heading = '每月利潤走勢';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = now()->subMonths(6)->startOfMonth();
        $end = now()->endOfMonth();

        $period = new \DatePeriod(
            $start,
            new \DateInterval('P1M'),
            $end->copy()->addDay()
        );

        $months = [];
        foreach ($period as $date) {
            $months[$date->format('Y-m')] = [
                'profits' => 0,
                'costs' => 0,
            ];
        }

        $profitsData = ProductProfit::query()
            ->selectRaw("DATE_FORMAT(order_completed_time, '%Y-%m') as month, SUM(total_profit) as aggregate")
            ->whereBetween('order_completed_time', [$start, $end])
            ->groupBy('month')
            ->get();

        foreach ($profitsData as $data) {
            if (isset($months[$data->month])) {
                $months[$data->month]['profits'] = (float) $data->aggregate;
            }
        }

        $costsData = Cost::query()
            ->selectRaw("DATE_FORMAT(incurred_time, '%Y-%m') as month, SUM(amount) as aggregate")
            ->whereBetween('incurred_time', [$start, $end])
            ->groupBy('month')
            ->get();

        foreach ($costsData as $data) {
            if (isset($months[$data->month])) {
                $months[$data->month]['costs'] = (float) $data->aggregate;
            }
        }

        $labels = array_keys($months);
        $profits = array_column($months, 'profits');
        $costs = array_column($months, 'costs');

        $netProfits = array_map(function ($profit, $cost) {
            return $profit - $cost;
        }, $profits, $costs);

        return [
            'datasets' => [
                [
                    'label' => '商品收益',
                    'data' => $profits,
                    'borderColor' => 'rgb(54, 162, 235)',
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => '淨利潤',
                    'data' => $netProfits,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => '固定成本',
                    'data' => $costs,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
