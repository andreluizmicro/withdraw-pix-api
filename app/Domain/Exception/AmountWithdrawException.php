<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class AmountWithdrawException extends DomainError
{
    public static function invalidAmount(): self
    {
        return new self('Invalid amount');
    }
}
