<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductProfit extends Pivot
{
    protected $table = 'product_profits';

    protected $guarded = [];

    protected $casts = [
        'order_completed_time' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function totalSalesPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->sales_price * $this->quantity,
        );
    }

    public function totalCostPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->cost_price * $this->quantity + $this->platform_fee,
        );
    }

    public function orderTotalPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->total_price ?? 0,
        );
    }

    public function orderBuyerTotalPayment(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->buyer_total_payment ?? 0,
        );
    }

    public function orderShopeeSubsidy(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->shopee_subsidy ?? 0,
        );
    }

    public function orderShopeeCoinDeduction(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->shopee_coin_deduction ?? 0,
        );
    }

    public function orderCreditCardPromotionDiscount(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->credit_card_promotion_discount ?? 0,
        );
    }

    public function orderShopVoucherDiscount(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->shop_voucher_discount ?? 0,
        );
    }

    public function orderShopShopeeCoinReturn(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->shop_shopee_coin_return ?? 0,
        );
    }

    public function orderVoucher(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->voucher ?? 0,
        );
    }

    public function orderTransactionFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->transaction_fee ?? 0,
        );
    }

    public function orderOtherServiceFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->other_service_fee ?? 0,
        );
    }

    public function orderPaymentProcessingFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->payment_processing_fee ?? 0,
        );
    }

    public function orderBuyerShippingFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->buyer_shipping_fee ?? 0,
        );
    }

    public function orderShopeeShippingFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->shopee_shipping_fee ?? 0,
        );
    }

    public function orderReturnShippingFee(): Attribute
    {
        return Attribute::make(
            get: fn (): float => $this->order->return_shipping_fee ?? 0,
        );
    }
}
