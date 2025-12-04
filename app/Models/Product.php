<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name ?? $this->shopee_name ?? '未命名商品',
        );
    }

    protected function costPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->cost_price_rmb === null) {
                    return null;
                }

                $setting = Setting::first();

                if ($setting?->rmb_to_twd_rate) {
                    $rate = $setting->rmb_to_twd_rate;
                } else {
                    return null;
                }

                return number_format($this->cost_price_rmb * $rate, 2);
            }
        );
    }
}
