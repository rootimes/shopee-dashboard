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
                if (is_null($this->cost_price_rmb)) {
                    return 0;
                }

                $rmbToTwdRateSetting = Setting::where('key', 'rmb_to_twd_rate')->first();

                if ($rmbToTwdRateSetting?->value) {
                    $rate = $rmbToTwdRateSetting->value;
                } else {
                    return 0;
                }

                return number_format($this->cost_price_rmb * $rate, 2);
            }
        );
    }
}
