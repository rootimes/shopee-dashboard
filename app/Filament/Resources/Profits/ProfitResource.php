<?php

namespace App\Filament\Resources\Profits;

use App\Filament\Resources\Profits\Pages\ListProfits;
use App\Filament\Resources\Profits\Pages\ViewProfit;
use App\Filament\Resources\Profits\RelationManagers\OrderRelationManager;
use App\Filament\Resources\Profits\Schemas\ProfitInfolist;
use App\Filament\Resources\Profits\Tables\ProfitsTable;
use App\Models\ProductProfit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfitResource extends Resource
{
    protected static ?string $model = ProductProfit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 4;

    public static function infolist(Schema $schema): Schema
    {
        return ProfitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProfits::route('/'),
            'view' => ViewProfit::route('/{record}'),
        ];
    }
}
