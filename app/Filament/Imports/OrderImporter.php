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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

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
            ImportColumn::make('shopee_coin_deduction')->label('蝦幣折抵')->requiredMapping()->guess(['蝦幣折抵']),
            ImportColumn::make('credit_card_promotion_deduction')->label('銀行信用卡活動折抵')->requiredMapping()->guess(['銀行信用卡活動折抵']),
            ImportColumn::make('shop_voucher_discount')->label('賣場優惠券')->requiredMapping()->guess(['賣場優惠券']),
            ImportColumn::make('shop_shopee_coin_return')->label('賣家蝦幣回饋券')->requiredMapping()->guess(['賣家蝦幣回饋券']),
            ImportColumn::make('shopee_voucher_discount')->label('優惠券')->requiredMapping()->guess(['優惠券']),
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

            ImportColumn::make('product_id')->label('商品選項貨號')->requiredMapping()->guess(['商品選項貨號'])->fillRecordUsing(fn () => []),
            ImportColumn::make('quantity')->label('數量')->requiredMapping()->guess(['數量'])->fillRecordUsing(fn () => []),
            ImportColumn::make('sales_price')->label('商品活動價格')->requiredMapping()->guess(['商品活動價格'])->fillRecordUsing(fn () => []),
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
        $body = 'Your order import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        $product = Product::find($this->data['product_id']);

        $productId = $this->data['product_id'] ?? null;

        if ($this->shouldSkipRow($productId)) {
            return;
        }

        [
            'totalPrice' => $totalPrice,
            'salesPrice' => $salesPrice,
            'quantity' => $quantity,
            'productCostPrice' => $productCostPrice,
        ] = $this->getProfitItems($product);

        $productTotalPrice = $this->calcProductTotalPrice($salesPrice, $quantity);

        $shopeeVoucherDiscount = $this->getField('shopee_voucher_discount');
        $shopeeDeduction = $this->getField('shopee_coin_deduction')
            + $this->getField('credit_card_promotion_deduction');
        $shopVoucherDiscount = $this->getField('shop_voucher_discount');
        $shopShopeeCoinReturn = $this->getField('shop_shopee_coin_return');

        $discountBase = $shopVoucherDiscount + $shopeeVoucherDiscount + $shopeeDeduction;

        $productOrderRatio = $this->calcProductOrderRatio(
            $productTotalPrice,
            $totalPrice,
            $discountBase,
        );

        $platformFee = $this->calcFee($productOrderRatio);

        $shopeeDiscountAmount = $shopeeVoucherDiscount * $productOrderRatio;
        $shopeeDeductionAmount = $shopeeDeduction * $productOrderRatio;
        $shopDiscountAmount = $shopVoucherDiscount * $productOrderRatio;
        $shopShopeeCoinReturnAmount = $shopShopeeCoinReturn * $productOrderRatio;

        $totalProfit =
            ($salesPrice - $productCostPrice) * $quantity
            - $shopShopeeCoinReturnAmount
            - $shopDiscountAmount
            - $platformFee;

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
                    'shopee_deduction_amount' => $shopeeDeductionAmount,
                    'shop_discount_amount' => $shopDiscountAmount,
                    'shop_shopee_coin_return_amount' => $shopShopeeCoinReturnAmount,
                    'shopee_discount_amount' => $shopeeDiscountAmount,
                    'product_order_ratio' => $productOrderRatio,
                    'cost_price' => $productCostPrice,
                    'total_profit' => $totalProfit,
                ],
            );
        } catch (\Exception $e) {
            Log::error('Error saving product profit', [
                'order_id' => $this->data['id'],
                'product_id' => $productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function shouldSkipRow(?string $productId): bool
    {
        if (
            $this->data['status'] !== OrderStatus::COMPLETED &&
            $this->data['status'] !== OrderStatus::REVIEW
        ) {
            return true;
        }

        if (is_null($productId)) {
            Log::info('Skipping order profit calculation', [
                'order_id' => $this->data['id'],
                'status' => $this->data['status']?->label(),
                'product_id' => $productId,
            ]);

            return true;
        }

        return false;
    }

    private function getProfitItems(Product $product): array
    {
        $totalPrice = $this->data['total_price'] ?? 0;
        $salesPrice = $this->data['sales_price'] ?? 0;
        $quantity = $this->data['quantity'] ?? 0;
        $productCostPrice = $product->cost_price ?? 0;

        return [
            'totalPrice' => $totalPrice,
            'salesPrice' => $salesPrice,
            'quantity' => $quantity,
            'productCostPrice' => $productCostPrice,
        ];
    }

    private function calcProductTotalPrice(float $salesPrice, int $quantity): float
    {
        return (float) ($salesPrice * $quantity);
    }

    private function getField(string $field): float
    {
        return (float) ($this->data[$field] ?? 0);
    }

    private function calcFee(float $ratio): float
    {
        return (float) (
            ($this->data['transaction_fee'] ?? 0)
            + ($this->data['other_service_fee'] ?? 0)
            + ($this->data['payment_processing_fee'] ?? 0)
        ) * $ratio;
    }

    private function calcProductOrderRatio(float $productTotalPrice, float $totalPrice, float $discount): float
    {
        $denominator = $totalPrice + $discount;

        return $denominator > 0
            ? $productTotalPrice / $denominator
            : 0.0;
    }
}
