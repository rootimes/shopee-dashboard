<?php

namespace App\Filament\Clusters\Profits\Resources\Products\Pages;

use App\Filament\Clusters\Profits\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
