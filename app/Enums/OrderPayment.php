<?php

namespace App\Enums;

enum OrderPayment: int
{
    case CREDIT_CARD = 1;
    case CASH_ON_DELIVERY = 2;
    case SHOPEE_PAY_LATER = 3;

    public function label(): string
    {
        return match ($this) {
            self::CREDIT_CARD => '信用卡',
            self::SHOPEE_PAY_LATER => '蝦皮晚點付',
            self::CASH_ON_DELIVERY => '貨到付款',
        };
    }

    public static function fromLabel(string $label): ?self
    {
        return match ($label) {
            '信用卡' => self::CREDIT_CARD,
            '貨到付款' => self::CASH_ON_DELIVERY,
            '蝦皮晚點付' => self::SHOPEE_PAY_LATER,
            default => null,
        };
    }
}
