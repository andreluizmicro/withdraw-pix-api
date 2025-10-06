<?php

declare(strict_types=1);

namespace App\Domain\Helper;

class Money
{
    public static function formatToBRL(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}