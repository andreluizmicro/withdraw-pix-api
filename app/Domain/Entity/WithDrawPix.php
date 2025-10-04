<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Uuid;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;

class WithDrawPix
{
    public function __construct(
        private Uuid $id,
        private Uuid $accountWithdrawId,
        private PixType $type,
        private PixKey $key,
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function accountWithdrawId(): Uuid
    {
        return $this->accountWithdrawId;
    }

    public function type(): PixType
    {
        return $this->type;
    }

    public function key(): PixKey
    {
        return $this->key;
    }
}
