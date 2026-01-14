<?php

namespace App\Filament\Resources\Profits\Schemas;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfitInfolist
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->columns(1)
            ->components([
                Section::make('訂單資訊')
                    ->schema([
                        TextEntry::make('order_id')
                            ->label('訂單編號')
                            ->url(
                                fn ($record) => $record->order
                                    ? OrderResource::getUrl('view', ['record' => $record->order_id])
                                    : null
                            )
                            ->color('info'),

                        TextEntry::make('order_completed_time')
                            ->label('訂單完成時間')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('商品銷售資訊')
                    ->schema([
                        TextEntry::make('display_name')
                            ->label('商品名稱'),

                        TextEntry::make('sales_price')
                            ->label('銷售價格')
                            ->money('TWD'),

                        TextEntry::make('quantity')
                            ->label('銷售數量'),

                        TextEntry::make('total_sales_price')
                            ->label('總銷售價格')
                            ->money('TWD'),

                        TextEntry::make('total_profit')
                            ->label('利潤')
                            ->money('TWD')
                            ->color(
                                fn ($state) => $state >= 0 ? 'success' : 'danger'
                            ),

                        TextEntry::make('product_id')
                            ->label('商品詳細')
                            ->url(
                                fn ($record) => $record->product_id
                                    ? ProductResource::getUrl('edit', ['record' => $record->product_id])
                                    : null
                            )
                            ->state('前往')
                            ->color('info'),
                    ])
                    ->columns(3),

                Section::make('成本與手續費 - 依銷售金額佔比')
                    ->schema([
                        TextEntry::make('platform_fee')
                            ->label('平台手續費(-)')
                            ->money('TWD'),

                        TextEntry::make('total_cost_price')
                            ->label('總成本價格(-)')
                            ->money('TWD'),

                        TextEntry::make('shopee_deduction_amount')
                            ->label('蝦皮折抵金額')
                            ->money('TWD'),

                        TextEntry::make('shop_discount_amount')
                            ->label('賣場折扣金額(-)')
                            ->money('TWD'),

                        TextEntry::make('shop_shopee_coin_return_amount')
                            ->label('賣場蝦幣回饋金額(-)')
                            ->money('TWD'),

                        TextEntry::make('shopee_discount_amount')
                            ->label('蝦皮折扣金額')
                            ->money('TWD'),

                        TextEntry::make('product_order_ratio')
                            ->label('商品訂單佔比')
                            ->suffix('%')
                            ->formatStateUsing(fn ($state) => $state * 100),
                    ])
                    ->columns(3),

                Section::make('訂單金額總覽')
                    ->schema([
                        TextEntry::make('order_total_price')
                            ->label('商品總價')
                            ->money('TWD'),

                        TextEntry::make('order_buyer_total_payment')
                            ->label('買家總支付金額')
                            ->money('TWD'),

                        TextEntry::make('order_shopee_subsidy')
                            ->label('蝦皮補貼金額')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('訂單折扣與優惠')
                    ->schema([
                        TextEntry::make('order_shopee_coin_deduction')
                            ->label('蝦幣折抵')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_credit_card_promotion_deduction')
                            ->label('銀行信用卡活動折抵')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_shop_voucher_discount')
                            ->label('賣場優惠券')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_shop_shopee_coin_return')
                            ->label('賣家蝦幣回饋券')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_voucher')
                            ->label('優惠券')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('訂單手續費')
                    ->schema([
                        TextEntry::make('order_transaction_fee')
                            ->label('成交手續費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_other_service_fee')
                            ->label('其他服務費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_payment_processing_fee')
                            ->label('金流與系統處理費')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),

                Section::make('訂單運費資訊')
                    ->schema([
                        TextEntry::make('order_buyer_shipping_fee')
                            ->label('買家支付運費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_shopee_shipping_fee')
                            ->label('蝦皮補助運費')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_return_shipping_fee')
                            ->label('退貨運費')
                            ->money('TWD')
                            ->placeholder('0'),
                    ])
                    ->columns(3),
            ]);
    }
}
