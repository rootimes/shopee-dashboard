<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'status' => \App\Enums\OrderStatus::class,
        'shipping_option' => \App\Enums\OrderShipping::class,
        'payment_method' => \App\Enums\OrderPayment::class,
        'note' => 'array',
        'ordered_at' => 'datetime',
        'buyer_payment_time' => 'datetime',
        'actual_shipment_time' => 'datetime',
        'completed_time' => 'datetime',
    ];
}
