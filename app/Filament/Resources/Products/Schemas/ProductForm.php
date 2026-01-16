<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Grid::make(1)
                    ->schema([
                        Grid::make(2)
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('id')
                                    ->label('商品ID')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('tmp_id')
                                    ->label('備用 ID')
                                    ->maxLength(255),
                            ])->columnSpan(1),

                        TextInput::make('name')
                            ->label('商品名稱')
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('shopee_name')
                            ->label('蝦皮商品規格名稱')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Grid::make(2)
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('cost_price_rmb')
                                    ->label('人民幣成本價')
                                    ->numeric()
                                    ->step(0.01)
                                    ->prefix('¥'),

                                TextInput::make('stock')
                                    ->label('庫存數量')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(0),
                            ]),
                    ])->columnSpan(1),

                FileUpload::make('image_url')
                    ->label('商品圖片網址')
                    ->columnSpan(1),
            ]);
    }
}
