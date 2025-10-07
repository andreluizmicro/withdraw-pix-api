<?php

declare(strict_types=1);

namespace App\Application\DTO\WithdrawPix;

class CreateWithdrawPixErrorInputDTO
{
    public function __construct(
        public string $accountWithdrawId,
        public string $type,
        public string $key,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            accountWithdrawId: $data['id'],
            type: $data['pix_type'],
            key: $data['pix_key'],
        );
    }

    public function toArray(): array
    {
        return [
            'account_withdraw_id' => $this->accountWithdrawId,
            'type' => $this->type,
            'key' => $this->key,
        ];
    }
}
