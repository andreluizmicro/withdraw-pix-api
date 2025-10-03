<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class DepositException extends DomainError
{
    public static function depositCannotBeNegative(): self
    {
        return new self('Deposit amount must be at least R$ 0,01');
    }
}
