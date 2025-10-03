<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\NameException;

class Name
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 255;

    public readonly string $value;

    /**
     * @throws NameException
     */
    public function __construct(string $value)
    {
        if (empty($value)) {
            throw NameException::nameCannotBeNull();
        }

        $this->value = strtoupper($value);

        if (! $this->isValid()) {
            throw NameException::nameLengthInvalid(self::MIN_LENGTH, self::MAX_LENGTH);
        }
    }

    private function isValid(): bool
    {
        return strlen($this->value) >= self::MIN_LENGTH && strlen($this->value) <= self::MAX_LENGTH;
    }
}
