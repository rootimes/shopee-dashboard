<?php

namespace App\Filament\Clusters\Profits\Resources\Products;

use App\Filament\Clusters\Profits\ProfitsCluster;
use App\Filament\Clusters\Profits\Resources\Products\Pages\ListProducts;
use App\Filament\Clusters\Profits\Resources\Products\Pages\ViewProduct;
use App\Filament\Clusters\Profits\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Clusters\Profits\Resources\Products\Tables\ProductsTable;
use App\Models\ProductProfit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = ProductProfit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = ProfitsCluster::class;

    public static function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'view' => ViewProduct::route('/{record}'),
        ];
    }
}
