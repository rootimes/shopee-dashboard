<?php

namespace App\Filament\Widgets;

use App\Models\ProductProfit;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProfitsChart extends ChartWidget
{
    protected ?string $heading = '每月利潤走勢';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $profitsData = Trend::model(ProductProfit::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->dateColumn('order_completed_time')
            ->sum('total_profit');

        $costsData = Trend::model(\App\Models\Cost::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->dateColumn('incurred_time')
            ->sum('amount');

        $labels = $profitsData->map(fn(TrendValue $value) => $value->date);
        $profits = $profitsData->map(fn(TrendValue $value) => $value->aggregate);
        $costs = $costsData->map(fn(TrendValue $value) => $value->aggregate);

        $netProfits = $profits->map(function ($profit, $index) use ($costs) {
            return $profit - ($costs[$index] ?? 0);
        });

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
