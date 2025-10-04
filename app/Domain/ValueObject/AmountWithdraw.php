<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\AmountWithdrawException;

class AmountWithdraw
{
    private const MIN_WITHDRAW_AMOUNT = 0.01;

    /**
     * @throws AmountWithdrawException
     */
    public function __construct(
        private readonly float $value,
    ) {
        if (! $this->isValid()) {
            throw AmountWithdrawException::invalidAmount();
        }
    }

    public function isValid(): bool
    {
        return $this->value >= self::MIN_WITHDRAW_AMOUNT;
    }

    public function value(): float
    {
        return $this->value;
    }
}
