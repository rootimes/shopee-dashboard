<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProfitsChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = '資訊總覽';

    public function getWidgets(): array
    {
        return [
            ProfitsChart::class,
        ];
    }
}
