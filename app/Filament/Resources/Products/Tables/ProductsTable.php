<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('商品選項貨號')->sortable()->searchable(),
                ImageColumn::make('image_url')->label('商品圖片'),
                TextColumn::make('display_name')->label('規格名稱')->sortable()->searchable()->limit(50),
                TextColumn::make('stock')->label('庫存數量')->sortable(),
                TextColumn::make('cost_price')->label('成本價')->sortable()->money('CNY'),
                TextColumn::make('created_at')->label('建立時間')->sortable()->dateTime(),
                TextColumn::make('updated_at')->label('最後更新時間')->sortable()->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
