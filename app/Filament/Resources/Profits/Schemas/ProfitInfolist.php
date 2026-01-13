<?php

namespace App\Filament\Resources\Profits\Schemas;

use Dom\Text;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

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
                            ->copyable(),

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
                                fn($state) =>
                                $state >= 0 ? 'success' : 'danger'
                            ),
                    ])
                    ->columns(3),

                Section::make('成本與利潤')
                    ->schema([
                        TextEntry::make('platform_fee')
                            ->label('平台手續費')
                            ->money('TWD'),

                        TextEntry::make('cost_price')
                            ->label('商品成本價格')
                            ->money('TWD'),

                        TextEntry::make('total_cost_price')
                            ->label('總成本價格')
                            ->money('TWD'),

                        TextEntry::make('discount_amount')
                            ->label('折扣金額')
                            ->money('TWD'),
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

                        TextEntry::make('sales_product_ratio')
                            ->label('商品銷售佔比')
                            ->suffix('%')
                            ->formatStateUsing(fn($state) => $state * 100),
                    ])
                    ->columns(3),

                Section::make('訂單折扣與優惠')
                    ->schema([
                        TextEntry::make('order_shopee_coin_deduction')
                            ->label('蝦皮幣抵扣')
                            ->money('TWD')
                            ->placeholder('0'),

                        TextEntry::make('order_credit_card_promotion_discount')
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
