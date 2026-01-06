<?php

namespace App\Filament\Imports;

use App\Enums\OrderPayment;
use App\Enums\OrderShipping;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Filament\Notifications\Notification;

class OrderImporter extends Importer
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')->label('訂單編號')->requiredMappingForNewRecordsOnly()->guess(['訂單編號']),
            ImportColumn::make('status')->label('訂單狀態')->requiredMapping()->guess(['訂單狀態'])
                ->castStateUsing(function (?string $state): ?OrderStatus {
                    return OrderStatus::fromLabel($state);
                }),
            ImportColumn::make('failure_reason')->label('不成立原因')->guess(['不成立原因']),
            ImportColumn::make('ordered_at')->label('訂單成立日期')->requiredMapping()->guess(['訂單成立日期'])
                ->castStateUsing(function (?string $state): ?string {
                    return $state === '-' ? null : $state;
                }),
            ImportColumn::make('total_price')->label('商品總價')->requiredMapping()->guess(['商品總價']),
            ImportColumn::make('buyer_shipping_fee')->label('買家支付運費')->requiredMapping()->guess(['買家支付運費']),
            ImportColumn::make('shopee_shipping_fee')->label('蝦皮補助運費')->requiredMapping()->guess(['蝦皮補助運費']),
            ImportColumn::make('return_shipping_fee')->label('退貨運費')->requiredMapping()->guess(['退貨運費']),
            ImportColumn::make('buyer_total_payment')->label('買家總支付金額')->requiredMapping()->guess(['買家總支付金額']),
            ImportColumn::make('shopee_subsidy')->label('蝦皮補貼金額')->requiredMapping()->guess(['蝦皮補貼金額']),
            ImportColumn::make('shopee_coin_deduction')->label('蝦皮幣抵扣')->requiredMapping()->guess(['蝦幣折抵']),
            ImportColumn::make('credit_card_promotion_discount')->label('銀行信用卡活動折抵')->requiredMapping()->guess(['銀行信用卡活動折抵']),
            ImportColumn::make('shop_voucher_discount')->label('賣場優惠券')->requiredMapping()->guess(['賣場優惠券']),
            ImportColumn::make('shop_shopee_coin_return')->label('賣家蝦幣回饋券')->requiredMapping()->guess(['賣家蝦幣回饋券']),
            ImportColumn::make('voucher')->label('優惠券')->requiredMapping()->guess(['優惠券']),
            ImportColumn::make('transaction_fee')->label('成交手續費')->requiredMapping()->guess(['成交手續費']),
            ImportColumn::make('other_service_fee')->label('其他服務費')->requiredMapping()->guess(['其他服務費']),
            ImportColumn::make('payment_processing_fee')->label('金流與系統處理費')->requiredMapping()->guess(['金流與系統處理費']),
            ImportColumn::make('installment_plan')->label('分期付款期數')->requiredMapping()->guess(['分期付款期數']),
            ImportColumn::make('city')->label('城市')->requiredMapping()->guess(['城市']),
            ImportColumn::make('district')->label('行政區')->requiredMapping()->guess(['行政區']),
            ImportColumn::make('shipping_option')->label('寄送方式')->requiredMapping()->guess(['寄送方式'])
                ->castStateUsing(function (?string $state): ?OrderShipping {
                    return OrderShipping::fromLabel($state);
                }),
            ImportColumn::make('payment_method')->label('付款方式')->requiredMapping()->guess(['付款方式'])
                ->castStateUsing(function (?string $state): ?OrderPayment {
                    return OrderPayment::fromLabel($state);
                }),
            ImportColumn::make('buyer_payment_time')->label('買家付款時間')->requiredMapping()->guess(['買家付款時間'])
                ->castStateUsing(function (?string $state): ?string {
                    return $state === '-' ? null : $state;
                }),
            ImportColumn::make('actual_shipment_time')->label('實際出貨時間')->requiredMapping()->guess(['實際出貨時間'])
                ->castStateUsing(function (?string $state): ?string {
                    return $state === '-' ? null : $state;
                }),
            ImportColumn::make('completed_time')->label('訂單完成時間')->requiredMapping()->guess(['訂單完成時間'])
                ->castStateUsing(function (?string $state): ?string {
                    return $state === '-' ? null : $state;
                }),
            ImportColumn::make('buyer_note')->label('買家備註')->guess(['買家備註']),
            ImportColumn::make('note')->label('備註')->guess(['備註']),

            // product info

            ImportColumn::make('product_id')->label('商品選項貨號')->requiredMapping()->guess(['商品選項貨號'])->fillRecordUsing(fn() => []),
            ImportColumn::make('quantity')->label('數量')->requiredMapping()->guess(['數量'])->fillRecordUsing(fn() => []),
            ImportColumn::make('sales_price')->label('商品活動價格')->requiredMapping()->guess(['商品活動價格'])->fillRecordUsing(fn() => []),
        ];
    }

    public function resolveRecord(): Order
    {
        return Order::firstOrNew([
            'id' => $this->data['id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your order import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        if ($this->shouldSkipRow()) {
            return;
        }

        $productOrderRate = $this->data['total_price'] / $this->data['buyer_total_payment'];

        $product = Product::find($this->data['product_id']);

        $productCostPrice = $product ? $product->cost_price : 0;

        $platformFee = ($this->data['transaction_fee']
            + $this->data['other_service_fee']
            + $this->data['payment_processing_fee']) * $productOrderRate;

        $discountAmount = ($this->data['shopee_coin_deduction']
            + $this->data['credit_card_promotion_discount']
            + $this->data['shop_voucher_discount']
            + $this->data['shop_shopee_coin_return']
            + $this->data['voucher']) * $productOrderRate;

        $totalProfit = ($this->data['sales_price'] - $productCostPrice) * $this->data['quantity']
            - $platformFee
            - $discountAmount;

        try {
            $this->record->profits()->updateOrCreate(
                [
                    'order_id' => $this->record->id,
                    'product_id' => $this->data['product_id'],
                ],
                [
                    'display_name' => $product ? $product->display_name : '未命名商品',
                    'sales_price' => $this->data['sales_price'],
                    'quantity' => $this->data['quantity'],
                    'order_completed_time' => $this->data['completed_time'],
                    'platform_fee' => $platformFee,
                    'discount_amount' => $discountAmount,
                    'cost_price' => $productCostPrice,
                    'total_profit' => $totalProfit,
                ],
            );
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error saving product profit for order ID ' . $this->data['id'] . ': ' . $e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    private function shouldSkipRow(): bool
    {
        if ($this->data['total_price'] === 0) {
            return true;
        }
        return false;
    }
}
