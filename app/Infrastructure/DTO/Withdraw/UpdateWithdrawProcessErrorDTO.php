<?php

declare(strict_types=1);

namespace App\Infrastructure\DTO\Withdraw;

class UpdateWithdrawProcessErrorDTO
{
    public function __construct(
        public string $accountWithdrawId,
        public bool $error,
        public ?string $errorReason = null,
        public ?bool $done = false,
    ) {
    }
}
