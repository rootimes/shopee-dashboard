<?php

namespace App\Enums;

enum OrderShipping: int
{
    case SHOPEE_STORE_TO_STORE = 1;
    case SEVELEN_ELEVEN = 2;
    case FAMILY_MART = 3;
    case OK_MART = 4;
    case HI_LIFE_ECONOMY = 5;
    case HI_LIFE = 6;
    case POST_OFFICE = 7;
    case HOME_DELIVERY_STANDARD = 8;

    public function label(): string
    {
        return match ($this) {
            self::SHOPEE_STORE_TO_STORE => '蝦皮店到店',
            self::SEVELEN_ELEVEN => '7-ELEVEN',
            self::FAMILY_MART => '全家',
            self::OK_MART => 'OK Mart',
            self::HI_LIFE_ECONOMY => '萊爾富-經濟包',
            self::HI_LIFE => '萊爾富',
            self::POST_OFFICE => '中華郵政',
            self::HOME_DELIVERY_STANDARD => '店到家宅配-標準包裹',
            default => '未匹配',
        };
    }

    public static function fromLabel(string $label): ?self
    {
        return match ($label) {
            '蝦皮店到店' => self::SHOPEE_STORE_TO_STORE,
            '7-ELEVEN' => self::SEVELEN_ELEVEN,
            '全家' => self::FAMILY_MART,
            'OK Mart' => self::OK_MART,
            '萊爾富-經濟包' => self::HI_LIFE_ECONOMY,
            '萊爾富' => self::HI_LIFE,
            '中華郵政' => self::POST_OFFICE,
            '店到家宅配 - 標準包裹' => self::HOME_DELIVERY_STANDARD,
            default => null,
        };
    }
}
