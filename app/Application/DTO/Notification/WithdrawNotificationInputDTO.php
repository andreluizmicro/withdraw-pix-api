<?php

declare(strict_types=1);

namespace App\Application\DTO\Notification;

class WithdrawNotificationInputDTO
{
    public function __construct(
        public ?string $accountId = null,
        public ?string $accountWithdrawId = null,
        public ?string $pixType = null,
        public ?string $pixKey = null,
        public ?float $amount = null,
    ) {
    }
}
