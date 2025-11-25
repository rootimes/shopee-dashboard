<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')->label('商品選項貨號')->requiredMappingForNewRecordsOnly()->guess(['商品選項貨號']),
            ImportColumn::make('shopee_name')->label('蝦皮商品規格名稱')->requiredMapping()->guess(['商品規格名稱']),
            ImportColumn::make('stock')->label('庫存數量')->requiredMapping()->guess(['庫存']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        if (blank($this->data['id'])) {
            return null;
        }
        
        return Product::firstOrNew([
            'id' => $this->data['id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
