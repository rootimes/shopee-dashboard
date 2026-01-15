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
        $data = Trend::model(ProductProfit::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->dateColumn('order_completed_time')
            ->sum('total_profit');

        return [
            'datasets' => [
                [
                    'label' => 'Total Profit',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
