<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('訂單編號')->sortable()->searchable(),
                TextColumn::make('status')->label('訂單狀態')->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->label() : null;
                    }),
                TextColumn::make('ordered_at')->label('訂單成立日期')->sortable()->dateTime(),
                TextColumn::make('total_price')->label('商品總價')->sortable()->money('TWD'),
                TextColumn::make('buyer_shipping_fee')->label('買家支付運費')->sortable()->money('TWD'),
                TextColumn::make('shopee_shipping_fee')->label('蝦皮補助運費')->sortable()->money('TWD'),
                TextColumn::make('return_shipping_fee')->label('退貨運費')->sortable()->money('TWD'),
                TextColumn::make('buyer_total_payment')->label('買家總支付金額')->sortable()->money('TWD'),
                TextColumn::make('shopee_subsidy')->label('蝦皮補貼金額')->sortable()->money('TWD'),
                TextColumn::make('shopee_coin_deduction')->label('蝦幣折抵')->sortable()->money('TWD'),
                TextColumn::make('credit_card_promotion_deduction')->label('銀行信用卡活動折抵')->sortable()->money('TWD'),
                TextColumn::make('shop_voucher_discount')->label('賣場優惠券')->sortable()->money('TWD'),
                TextColumn::make('shop_shopee_coin_return')->label('賣家蝦幣回饋券')->sortable()->money('TWD'),
                TextColumn::make('shopee_voucher_discount')->label('優惠券')->sortable()->money('TWD'),
                TextColumn::make('transaction_fee')->label('成交手續費')->sortable()->money('TWD'),
                TextColumn::make('other_service_fee')->label('其他服務費')->sortable()->money('TWD'),
                TextColumn::make('payment_processing_fee')->label('金流與系統處理費')->sortable()->money('TWD'),
                TextColumn::make('installment_plan')->label('分期付款期數')->sortable(),
                TextColumn::make('city')->label('城市')->sortable()->searchable(),
                TextColumn::make('district')->label('行政區')->sortable()->searchable(),
                TextColumn::make('shipping_option')->label('寄送方式')->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->label() : null;
                    }),
                TextColumn::make('payment_method')->label('付款方式')->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->label() : null;
                    }),
                TextColumn::make('buyer_payment_time')->label('買家付款時間')->sortable()->dateTime(),
                TextColumn::make('actual_shipment_time')->label('實際出貨時間')->sortable()->dateTime(),
                TextColumn::make('completed_time')->label('訂單完成時間')->sortable()->dateTime(),
                TextColumn::make('failure_reason')->label('不成立原因'),
                TextColumn::make('buyer_note')->label('買家備註')->searchable()->limit(50),
                TextColumn::make('note')->label('備註')->searchable()->limit(50),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('訂單狀態')
                    ->multiple()
                    ->options(fn () => OrderStatus::options()),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
