<?php

namespace App\Filament\Imports;

use App\Enums\OrderPayment;
use App\Enums\OrderShipping;
use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class OrderImporter extends Importer
{
    protected static ?string $model = Order::class;

    protected array $processedIds = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')->label('訂單編號')->requiredMapping()->guess(['訂單編號']),
            ImportColumn::make('status')->label('訂單狀態')->requiredMapping()->guess(['訂單狀態'])
                ->castStateUsing(function (?string $state): OrderStatus {
                    return OrderStatus::fromLabel($state);
                }),
            ImportColumn::make('failure_reason')->label('不成立原因')->guess(['不成立原因']),
            ImportColumn::make('ordered_at')->label('訂單成立日期')->requiredMapping()->guess(['訂單成立日期']),
            ImportColumn::make('total_price')->label('商品總價')->requiredMapping()->guess(['商品總價']),
            ImportColumn::make('buyer_shipping_fee')->label('買家支付運費')->requiredMapping()->guess(['買家支付運費']),
            ImportColumn::make('shopee_shipping_fee')->label('蝦皮補助運費')->requiredMapping()->guess(['蝦皮補助運費']),
            ImportColumn::make('return_shipping_fee')->label('退貨運費')->requiredMapping()->guess(['退貨運費']),
            ImportColumn::make('buyer_total_payment')->label('買家總支付金額')->requiredMapping()->guess(['買家總支付金額']),
            ImportColumn::make('shopee_subsidy')->label('蝦皮補貼金額')->requiredMapping()->guess(['蝦皮補貼金額']),
            ImportColumn::make('shopee_coin_deduction')->label('蝦皮幣抵扣')->requiredMapping()->guess(['蝦幣折抵']),
            ImportColumn::make('credit_card_promotion_discount')->label('銀行信用卡活動折抵')->requiredMapping()->guess(['銀行信用卡活動折抵']),
            ImportColumn::make('shop_voucher_discount')->label('賣場優惠券')->requiredMapping()->guess(['賣場優惠券']),
            ImportColumn::make('shop_coin_deduction')->label('賣家蝦幣回饋券')->requiredMapping()->guess(['賣家蝦幣回饋券']),
            ImportColumn::make('voucher')->label('優惠券')->requiredMapping()->guess(['優惠券']),
            ImportColumn::make('transaction_fee')->label('成交手續費')->requiredMapping()->guess(['成交手續費']),
            ImportColumn::make('other_service_fee')->label('其他服務費')->requiredMapping()->guess(['其他服務費']),
            ImportColumn::make('payment_processing_fee')->label('金流與系統處理費')->requiredMapping()->guess(['金流與系統處理費']),
            ImportColumn::make('installment_plan')->label('分期付款期數')->requiredMapping()->guess(['分期付款期數']),
            ImportColumn::make('city')->label('城市')->requiredMapping()->guess(['城市']),
            ImportColumn::make('district')->label('行政區')->requiredMapping()->guess(['行政區']),
            ImportColumn::make('shipping_option')->label('寄送方式')->requiredMapping()->guess(['寄送方式'])
                ->castStateUsing(function (?string $state): OrderShipping {
                    return OrderShipping::fromLabel($state);
                }),
            ImportColumn::make('payment_method')->label('付款方式')->requiredMapping()->guess(['付款方式'])
                ->castStateUsing(function (?string $state): OrderPayment {
                    return OrderPayment::fromLabel($state);
                }),
            ImportColumn::make('buyer_payment_time')->label('買家付款時間')->requiredMapping()->guess(['買家付款時間']),
            ImportColumn::make('actual_shipment_time')->label('實際出貨時間')->requiredMapping()->guess(['實際出貨時間']),
            ImportColumn::make('completed_time')->label('訂單完成時間')->requiredMapping()->guess(['訂單完成時間']),
            ImportColumn::make('buyer_note')->label('買家備註')->guess(['買家備註']),
            ImportColumn::make('note')->label('備註')->guess(['備註']),
        ];
    }

    public function resolveRecord(): Order
    {
        $existing = Order::query()->where('id', $this->data['id'])->first();

        return $existing ?? new Order;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
