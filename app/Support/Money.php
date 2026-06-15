<?php

namespace App\Support;

class Money
{
    /**
     * Convert decimal string to cents (integer)
     * "100.50" => 10050
     */
    public static function toCents(string|float|int $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    /**
     * Convert cents to decimal string
     */
    public static function toDecimal(int $amount): string
    {
        return number_format($amount / 100, 2, '.', '');
    }
}