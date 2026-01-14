<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;
    case TRANSIT = 2;
    case DELIVERED = 3;
    case REVIEW = 4;
    case COMPLETED = 5;
    case CANCELED = 6;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => '待出貨',
            self::TRANSIT => '運送中',
            self::DELIVERED => '已送達',
            self::REVIEW => '鑑賞期',
            self::COMPLETED => '已完成',
            self::CANCELED => '不成立',
            default => '未匹配',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::TRANSIT->value => self::TRANSIT->label(),
            self::DELIVERED->value => self::DELIVERED->label(),
            self::REVIEW->value => self::REVIEW->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            self::CANCELED->value => self::CANCELED->label(),
        ];
    }

    public static function fromLabel(string $label): ?self
    {
        return match ($label) {
            '待出貨' => self::PENDING,
            '運送中' => self::TRANSIT,
            '已送達' => self::DELIVERED,
            '已完成' => self::COMPLETED,
            '不成立' => self::CANCELED,
            default => str_contains($label, '鑑賞期') ? self::REVIEW : null,
        };
    }
}
