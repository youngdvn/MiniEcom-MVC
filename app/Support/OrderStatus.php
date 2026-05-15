<?php

namespace App\Support;

class OrderStatus
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';

    public static function orderLabel(string $status): string
    {
        return self::orderLabels()[$status] ?? $status;
    }

    public static function paymentLabel(string $status): string
    {
        return self::paymentLabels()[$status] ?? $status;
    }

    public static function paymentMethodLabel(?string $method): string
    {
        $map = [
            'cod' => 'COD',
            'bank_transfer' => 'Chuyển khoản',
            'ewallet' => 'Ví điện tử',
        ];

        if (!$method) {
            return '---';
        }

        return $map[$method] ?? $method;
    }

    public static function orderLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }

    public static function paymentLabels(): array
    {
        return [
            self::PAYMENT_UNPAID => 'Chưa thanh toán',
            self::PAYMENT_PAID => 'Đã thanh toán',
            self::PAYMENT_REFUNDED => 'Đã hoàn tiền',
        ];
    }
}

