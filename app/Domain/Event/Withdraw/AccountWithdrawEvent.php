<?php

declare(strict_types=1);

namespace App\Domain\Event\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Event\EventInterface;

abstract class AccountWithdrawEvent implements EventInterface
{
    public function __construct(
      public readonly AccountWithdraw $accountWithdraw,
    ) {
    }

    public function getProperties(): array
    {
        return [
            'id' => $this->accountWithdraw->id(),
            'account_id' => $this->accountWithdraw->accountId(),
            'method' => $this->accountWithdraw->method()->value,
            'amount' => $this->accountWithdraw->amount()->value(),
            'scheduled' => $this->accountWithdraw->schedule()->scheduled(),
            'scheduled_for' => $this->accountWithdraw->schedule()->date(),
            'done' => $this->accountWithdraw->done(),
            'error' => $this->accountWithdraw->error(),
            'error_reason' => $this->accountWithdraw->errorReason(),
        ];
    }
}
