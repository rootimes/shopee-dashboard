<?php

namespace App\Filament\Resources\Profits\Pages;

use App\Filament\Resources\Profits\ProfitResource;
use Filament\Resources\Pages\ListRecords;

class ListProfits extends ListRecords
{
    protected static string $resource = ProfitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
