<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Enum\PixType as PixTypeEnum;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Validation\DomainValidation;
use DomainException;

readonly class PixKey
{
    /**
     * @throws WithDrawPixException
     */
    public function __construct(
      private PixType $type,
      private string  $key,
    ) {
        if (! $this->isValid()) {
            throw WithDrawPixException::invalidPixKey();
        }
    }

    public function isValid(): bool
    {
        return match ($this->type->value()) {
            PixTypeEnum::EMAIL->value => DomainValidation::email($this->key),
            default => throw new DomainException("invalid pix type {$this->type}"),
        };
    }

    public function type(): PixType
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->key;
    }
}
