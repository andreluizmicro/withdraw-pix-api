<?php

declare(strict_types=1);

namespace App\Infrastructure\DTO\Withdraw;

class WithdrawAggregateDTO
{
    public function __construct(
        public readonly string $accountId,
        public readonly string $accountName,
        public readonly float $accountBalance,
        public readonly string $withdrawId,
        public readonly float $withdrawAmount,
        public readonly bool $withdrawScheduled,
        public readonly ?string $withdrawScheduledFor,
        public readonly string $pixId,
        public readonly string $pixKey,
        public readonly string $pixType
    ) {
    }
}
