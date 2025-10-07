<?php

declare(strict_types=1);

namespace App\Application\DTO\Schedule;

class ProcessScheduleInputDTO
{
    public function __construct(
        public string $accountWithdrawId,
    ) {
    }
}