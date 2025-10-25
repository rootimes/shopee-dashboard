<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'note' => 'array',
        'ordered_at' => 'datetime',
        'buyer_payment_time' => 'datetime',
        'actual_shipment_time' => 'datetime',
        'completed_time' => 'datetime',
    ];
}
