<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('訂單摘要')
                    ->schema([
                        TextEntry::make('id')
                            ->label('訂單編號')
                            ->copyable(),

                        TextEntry::make('status')
                            ->label('訂單狀態')
                            ->formatStateUsing(fn($state) => $state?->label() ?? '-'),

                        TextEntry::make('ordered_at')
                            ->label('訂單成立時間')
                            ->dateTime(),

                        TextEntry::make('completed_time')
                            ->label('訂單完成時間')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('failure_reason')
                            ->label('不成立原因')
                            ->placeholder('無')
                            ->visible(fn($record) => filled($record->failure_reason)),
                    ])
                    ->columns(4),

                Section::make('金額總覽')
                    ->schema([
                        TextEntry::make('total_price')
                            ->label('商品總價')
                            ->money('TWD'),

                        TextEntry::make('buyer_total_payment')
                            ->label('買家總支付金額')
                            ->money('TWD'),

                        TextEntry::make('shopee_subsidy')
                            ->label('蝦皮補貼金額')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('運費資訊')
                    ->schema([
                        TextEntry::make('buyer_shipping_fee')
                            ->label('買家支付運費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('shopee_shipping_fee')
                            ->label('蝦皮補助運費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('return_shipping_fee')
                            ->label('退貨運費')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('折扣與優惠')
                    ->schema([
                        TextEntry::make('shopee_coin_deduction')
                            ->label('蝦皮幣抵扣')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('credit_card_promotion_discount')
                            ->label('銀行信用卡活動折抵')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('shop_voucher_discount')
                            ->label('賣場優惠券')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('shop_shopee_coin_return')
                            ->label('賣家蝦幣回饋券')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('voucher')
                            ->label('優惠券')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('手續費')
                    ->schema([
                        TextEntry::make('transaction_fee')
                            ->label('成交手續費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('other_service_fee')
                            ->label('其他服務費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('payment_processing_fee')
                            ->label('金流與系統處理費')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('付款與物流')
                    ->schema([
                        TextEntry::make('payment_method')
                            ->label('付款方式')
                            ->formatStateUsing(fn($state) => $state?->label() ?? '-'),

                        TextEntry::make('installment_plan')
                            ->label('分期付款期數')
                            ->placeholder('-'),

                        TextEntry::make('shipping_option')
                            ->label('寄送方式')
                            ->formatStateUsing(fn($state) => $state?->label() ?? '-'),

                        TextEntry::make('city')
                            ->label('城市')
                            ->placeholder('-'),

                        TextEntry::make('district')
                            ->label('行政區')
                            ->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('時間軸')
                    ->schema([
                        TextEntry::make('buyer_payment_time')
                            ->label('買家付款時間')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('actual_shipment_time')
                            ->label('實際出貨時間')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('completed_time')
                            ->label('訂單完成時間')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('備註')
                    ->schema([
                        TextEntry::make('buyer_note')
                            ->label('買家備註')
                            ->placeholder('無'),

                        TextEntry::make('note')
                            ->label('備註')
                            ->placeholder('無'),
                    ])
                    ->columns(1),
            ]);
    }
}
