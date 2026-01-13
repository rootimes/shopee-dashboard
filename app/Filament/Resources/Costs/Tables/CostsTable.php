<?php

namespace App\Filament\Resources\Costs\Tables;

use App\Models\Cost;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('花費名稱')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('金額')
                    ->money('twd', true)
                    ->sortable(),
                TextColumn::make('incurred_time')
                    ->label('發生時間')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('描述')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('name')
                    ->label('花費名稱')
                    ->options(function () {
                        return Cost::query()
                            ->distinct()
                            ->pluck('name', 'name')
                            ->toArray();
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
