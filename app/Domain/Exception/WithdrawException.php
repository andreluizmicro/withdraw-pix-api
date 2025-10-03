<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class WithdrawException extends DomainError
{
    public static function cannotBeNegative(): self
    {
        return new self('WithDraw amount cannot be negative or zero');
    }

    public static function insufficientBalance(): self
    {
        return new self('Insufficient balance for withdrawal');
    }
}
