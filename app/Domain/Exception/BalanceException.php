<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class BalanceException extends DomainError
{
    public static function balanceCannotBeNegative(): self
    {
        return new self('Balance cannot be negative');
    }
}
