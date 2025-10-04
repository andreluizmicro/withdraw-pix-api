<?php

declare(strict_types=1);

namespace App\Application\DTO\Withdraw;

class CreateWithdrawInputDTO
{
    public function __construct(
        public string $accountId,
        public string $method,
        public string $pixType,
        public string $pixKey,
        public float $amount,
        public ?string $schedule = null,
    ) {
    }

    public static function fromArray(array $data): CreateWithdrawInputDTO
    {
        return new CreateWithdrawInputDTO(
            accountId: $data['account_id'],
            method: $data['method'],
            pixType: $data['pix']['type'],
            pixKey: $data['pix']['key'],
            amount: $data['amount'],
            schedule: $data['schedule'],
        );
    }
}
