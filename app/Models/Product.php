<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? $this->shopee_name ?? '未命名商品',
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
                
                if ($setting->rmb_to_cny_rate) {
                    $rate = $setting->rmb_to_cny_rate;
                } else {
                    return null;
                }
                
                return number_format($this->cost_price_rmb * $rate, 2);
            }
        );
    }
}
