<?php

namespace App\Models;

use App\Enums\OrderPayment;
use App\Enums\OrderShipping;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Guarded([])]
class Order extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'status' => OrderStatus::class,
        'shipping_option' => OrderShipping::class,
        'payment_method' => OrderPayment::class,
        'ordered_at' => 'datetime',
        'buyer_payment_time' => 'datetime',
        'actual_shipment_time' => 'datetime',
        'completed_time' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_profits', 'order_id', 'product_id')
            ->using(ProductProfit::class)
            ->withPivot([
                'id',
                'display_name',
                'sales_price',
                'quantity',
                'platform_fee',
                'product_order_ratio',
                'cost_price',
                'total_profit',
            ]);
    }

    public function profits()
    {
        return $this->hasMany(ProductProfit::class, 'order_id', 'id');
    }
}
