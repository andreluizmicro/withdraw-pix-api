<?php

declare(strict_types=1);

namespace App\Application\DTO\WithdrawPix;

class WithdrawPixInputDTO
{
    public function __construct(
        public string $accountId,
        public string $type,
        public string $key,
    ) {
    }

    public static function fromArray(array $data): WithdrawPixInputDTO
    {
        return new WithdrawPixInputDTO(
            $data['account_id'],
            $data['type'],
            $data['key'],
        );
    }
}
