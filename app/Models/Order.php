<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'status' => \App\Enums\OrderStatus::class,
        'shipping_option' => \App\Enums\OrderShipping::class,
        'payment_method' => \App\Enums\OrderPayment::class,
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
