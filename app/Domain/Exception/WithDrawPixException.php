<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class WithDrawPixException extends DomainError
{
    public static function invalidPixType(): self
    {
        return new self('Invalid pix type');
    }

    public static function invalidPixKey(): self
    {
        return new self('Invalid pix key');
    }
}
