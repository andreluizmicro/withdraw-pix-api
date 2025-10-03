<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class UuidException extends DomainError
{
    public static function invalidUuid(): self
    {
        return new self('Invalid UUID provided.');
    }
}
