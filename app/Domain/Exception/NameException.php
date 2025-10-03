<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class NameException extends DomainError
{
    public static function nameCannotBeNull(): self
    {
        return new self('Name cannot be null');
    }

    public static function nameLengthInvalid(int $minLength, int $maxLength): self
    {
        return new self("Name must be between {$minLength} and {$maxLength} characters");
    }
}
