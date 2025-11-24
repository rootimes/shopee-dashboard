<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use Filament\Support\Icons\Heroicon;
use App\Filament\Imports\ProductImporter;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('匯入商品')
                ->icon(Heroicon::ArrowUpTray)
                ->importer(ProductImporter::class),
            CreateAction::make(),
        ];
    }
}
