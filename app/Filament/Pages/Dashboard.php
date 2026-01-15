<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProfitsChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = '總覽';

    protected static ?string $navigationLabel = 'Home';

    public function getWidgets(): array
    {
        return [
            ProfitsChart::class,
        ];
    }
}
