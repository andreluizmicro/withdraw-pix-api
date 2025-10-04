<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\WithdrawMethod;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;

class AccountWithdraw
{
    public function __construct(
        private Uuid $id,
        private Uuid $accountId,
        private WithdrawMethod $method,
        private AmountWithdraw $amount,
        private Schedule $schedule,
        private bool $done,
        private bool $error,
        private string $errorReason,
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function accountId(): Uuid
    {
        return $this->accountId;
    }

    public function method(): WithdrawMethod
    {
        return $this->method;
    }

    public function amount(): AmountWithdraw
    {
        return $this->amount;
    }

    public function schedule(): Schedule
    {
        return $this->schedule;
    }

    public function done(): bool
    {
        return $this->done;
    }

    public function error(): bool
    {
        return $this->error;
    }

    public function errorReason(): string
    {
        return $this->errorReason;
    }
}
