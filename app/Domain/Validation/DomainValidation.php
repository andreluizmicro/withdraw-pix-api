<?php

declare(strict_types=1);

namespace App\Domain\Validation;

class DomainValidation
{
    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
