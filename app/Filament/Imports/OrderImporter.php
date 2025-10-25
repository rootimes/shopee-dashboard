<?php

namespace App\Filament\Imports;

use App\Models\Order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class OrderImporter extends Importer
{
    protected static ?string $model = Order::class;

    protected array $processedIds = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')->label('訂單編號')->requiredMapping()->guess(['訂單編號']),
            ImportColumn::make('total_price')->label('商品總價')->requiredMapping()->guess(['商品總價']),
        ];
    }

    public function resolveRecord(): Order
    {
        $existing = Order::query()->where('id', $this->data['id'])->first();

        return $existing ?? new Order();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
