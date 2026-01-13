<?php

namespace App\Filament\Resources\Costs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label('花費名稱')
                    ->required()
                    ->maxLength(255),
                TextInput::make('amount')
                    ->label('金額')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                DatePicker::make('incurred_time')
                    ->label('發生時間')
                    ->required()
                    ->date(),
                Textarea::make('description')
                    ->rows(3)
                    ->label('描述')
            ]);
    }
}
