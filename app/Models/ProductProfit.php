<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProfit extends Model
{
    protected $guarded = [];

    protected $casts = [
        'order_completed_time' => 'datetime',
    ];
}
