<?php

declare(strict_types=1);

namespace App\Domain\Event\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;

class AccountWithdrawPixCreatedEvent extends AccountWithdrawEvent
{
    public function __construct(
        public readonly AccountWithdraw $accountWithdraw,
        public readonly AccountWithdrawPix $accountWithdrawPix,
    ) {
    }

    public function getName(): string
    {
        return Events::ACCOUNT_WITHDRAW_CREATED->value;
    }

    public function getCategory(): string
    {
        return EventCategory::WITHDRAWN_CREATION->value;
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
            'done' => true,
            'error' => false,
            'error_reason' => null,
        ];
    }
}
