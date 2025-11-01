<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本訂單資訊')
                    ->schema([
                        TextEntry::make('id')->label('訂單編號'),
                        TextEntry::make('status')->label('訂單狀態')
                            ->formatStateUsing(fn($state) => $state?->label()),
                        TextEntry::make('failure_reason')->label('不成立原因'),
                        TextEntry::make('ordered_at')->label('訂單成立日期')
                            ->dateTime(),
                    ]),

                Section::make('價格明細')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('total_price')->label('商品總價')
                                    ->money('TWD'),
                                TextEntry::make('buyer_total_payment')->label('買家總支付金額')
                                    ->money('TWD'),
                                TextEntry::make('shopee_subsidy')->label('蝦皮補貼金額')
                                    ->money('TWD'),
                            ]),
                    ]),

                Section::make('運費資訊')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('buyer_shipping_fee')->label('買家支付運費')
                                    ->money('TWD'),
                                TextEntry::make('shopee_shipping_fee')->label('蝦皮補助運費')
                                    ->money('TWD'),
                                TextEntry::make('return_shipping_fee')->label('退貨運費')
                                    ->money('TWD'),
                            ]),
                    ]),

                Section::make('折扣與優惠')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('shopee_coin_deduction')->label('蝦皮幣抵扣')
                                    ->money('TWD'),
                                TextEntry::make('credit_card_promotion_discount')->label('銀行信用卡活動折抵')
                                    ->money('TWD'),
                                TextEntry::make('shop_voucher_discount')->label('賣場優惠券')
                                    ->money('TWD'),
                                TextEntry::make('shop_coin_deduction')->label('賣家蝦幣回饋券')
                                    ->money('TWD'),
                                TextEntry::make('voucher')->label('優惠券')
                                    ->money('TWD'),
                            ]),
                    ]),

                Section::make('手續費')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('transaction_fee')->label('成交手續費')
                                    ->money('TWD'),
                                TextEntry::make('other_service_fee')->label('其他服務費')
                                    ->money('TWD'),
                                TextEntry::make('payment_processing_fee')->label('金流與系統處理費')
                                    ->money('TWD'),
                            ]),
                    ]),

                Section::make('付款與物流資訊')
                    ->schema([
                        TextEntry::make('payment_method')->label('付款方式')
                            ->formatStateUsing(fn($state) => $state?->label()),
                        TextEntry::make('installment_plan')->label('分期付款期數'),
                        TextEntry::make('shipping_option')->label('寄送方式')
                            ->formatStateUsing(fn($state) => $state?->label()),
                        TextEntry::make('city')->label('城市'),
                        TextEntry::make('district')->label('行政區'),
                    ]),

                Section::make('時間軸')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('buyer_payment_time')->label('買家付款時間')
                                    ->dateTime(),
                                TextEntry::make('actual_shipment_time')->label('實際出貨時間')
                                    ->dateTime(),
                                TextEntry::make('completed_time')->label('訂單完成時間')
                                    ->dateTime(),
                            ]),
                    ]),

                Section::make('備註資訊')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('buyer_note')->label('買家備註')
                                    ->placeholder('無'),
                                TextEntry::make('note')->label('備註')
                                    ->placeholder('無'),
                            ]),
                    ]),
            ]);
    }
}
