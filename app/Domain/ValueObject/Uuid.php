<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\UuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    /**
     * @throws UuidException
     */
    public function __construct(public string $value)
    {
        if (! $this->isValid()) {
            throw UuidException::invalidUuid();
        }
    }

    /**
     * @throws UuidException
     */
    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    private function isValid(): bool
    {
       return RamseyUuid::isValid($this->value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
