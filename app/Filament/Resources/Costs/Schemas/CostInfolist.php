<?php

namespace App\Filament\Resources\Costs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextEntry::make('name')
                    ->label('花費名稱'),
                TextEntry::make('amount')
                    ->label('金額')
                    ->money('twd', true),
                TextEntry::make('incurred_time')
                    ->label('發生時間')
                    ->date(),
                TextEntry::make('description')
                    ->label('描述'),
            ]);
    }
}
