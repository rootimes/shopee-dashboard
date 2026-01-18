<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Imports\OrderImporter;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('匯入訂單')
                ->icon(Heroicon::ArrowUpTray)
                ->importer(OrderImporter::class),
        ];
    }
}
