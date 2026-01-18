<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Resources\Profits\ProfitResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
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
                            ->formatStateUsing(fn ($state) => $state?->label() ?? '-'),

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
                            ->visible(fn ($record) => filled($record->failure_reason)),
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
                            ->label('蝦幣折抵')
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
                            ->formatStateUsing(fn ($state) => $state?->label() ?? '-'),

                        TextEntry::make('installment_plan')
                            ->label('分期付款期數')
                            ->placeholder('-'),

                        TextEntry::make('shipping_option')
                            ->label('寄送方式')
                            ->formatStateUsing(fn ($state) => $state?->label() ?? '-'),

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

                Section::make('商品清單')
                    ->schema([
                        RepeatableEntry::make('products')
                            ->schema([
                                TextEntry::make('pivot.display_name')
                                    ->label('商品名稱'),

                                TextEntry::make('pivot.quantity')
                                    ->label('數量'),

                                TextEntry::make('pivot.sales_price')
                                    ->label('銷售價格')
                                    ->money('TWD'),

                                TextEntry::make('pivot.cost_price')
                                    ->label('成本價格')
                                    ->money('TWD'),

                                TextEntry::make('pivot.total_revenue')
                                    ->label('總營收')
                                    ->helperText('已減去折扣與手續費')
                                    ->money('TWD'),

                                TextEntry::make('pivot.platform_fee')
                                    ->label('平台手續費')
                                    ->money('TWD'),

                                TextEntry::make('pivot.product_order_ratio')
                                    ->label('商品訂單佔比')
                                    ->suffix('%')
                                    ->formatStateUsing(fn (?float $state) => $state !== null ? $state * 100 : null),

                                TextEntry::make('pivot.total_profit')
                                    ->label('利潤')
                                    ->money('TWD')
                                    ->color(fn (?float $state) => $state >= 0 ? 'success' : 'danger'),

                                TextEntry::make('pivot.id')
                                    ->label('商品利潤詳細')
                                    ->state('前往')
                                    ->url(
                                        fn ($record) => $record->pivot?->id
                                            ? ProfitResource::getUrl('view', ['record' => $record->pivot->id])
                                            : null
                                    )
                                    ->color('info'),
                            ])
                            ->columns(3),
                    ])
                    ->columns(1),
            ]);
    }
}
