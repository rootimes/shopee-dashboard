<?php

namespace App\Filament\Clusters\Profits\Resources\Products\Pages;

use App\Filament\Clusters\Profits\Resources\Products\ProductResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
