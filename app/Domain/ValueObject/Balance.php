<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\BalanceException;

class Balance
{
    public const MIN_VALUE = 0;

    /**
     * @throws BalanceException
     */
    public function __construct(public float $value)
    {
        if ($value < self::MIN_VALUE) {
            throw BalanceException::balanceCannotBeNegative();
        }
    }
}
