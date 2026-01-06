<?php

namespace App\Filament\Clusters\Profits\Resources\Products\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->copyable()->searchable()->sortable()->label('訂單編號'),
                TextColumn::make('display_name')->searchable()->label('商品名稱'),
                TextColumn::make('sales_price')->label('銷售價格'),
                TextColumn::make('quantity')->label('銷售數量'),
                TextColumn::make('total_sales_price')->label('總銷售價格')->getStateUsing(fn($record) => $record->sales_price * $record->quantity),
                TextColumn::make('platform_fee')->summarize(Sum::make()->label('總手續費'))->label('平台手續費'),
                TextColumn::make('discount_amount')->label('折扣金額'),
                TextColumn::make('cost_price')->label('商品成本價格'),
                TextColumn::make('total_profit')->sortable()->summarize(Sum::make()->label('總利潤'))->label('利潤'),
                TextColumn::make('order_completed_time')->label('訂單完成時間')->sortable()->dateTime(),
            ])
            ->filters([
                Filter::make('order_completed_time_range')
                    ->label('訂單完成時間區間')
                    ->schema([
                        DateTimePicker::make('from')
                            ->label('開始時間')
                            ->seconds(false),

                        DateTimePicker::make('until')
                            ->label('結束時間')
                            ->seconds(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['from'] ?? null),
                                fn(Builder $query) => $query->where('order_completed_time', '>=', $data['from']),
                            )
                            ->when(
                                filled($data['until'] ?? null),
                                fn(Builder $query) => $query->where('order_completed_time', '<=', $data['until']),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (filled($data['from'] ?? null)) {
                            $indicators[] = '開始：' . $data['from'];
                        }

                        if (filled($data['until'] ?? null)) {
                            $indicators[] = '結束：' . $data['until'];
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([])
            ->defaultSort('order_completed_time', 'desc');
    }
}
