<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Enum\PixType as PixTypeEnum;
use App\Domain\Exception\WithDrawPixException;

readonly class PixType
{
    /**
     * @throws WithDrawPixException
     */
    public function __construct(
        private string $type,
    ) {
        if (! $this->isValid()) {
            throw WithDrawPixException::invalidPixType();
        }
    }

    private function isValid(): bool
    {
        $values = array_map(fn($case) => $case->value, PixTypeEnum::cases());
        return in_array($this->type, $values);
    }

    public function value(): string
    {
        return $this->type;
    }
}
