<?php

declare(strict_types=1);

namespace App\Domain\Event\WithdrawPix;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;
use App\Domain\Event\EventInterface;

readonly class WithdrawPixProcessedEvent implements EventInterface
{
    public function __construct(
        public AccountWithdraw    $accountWithdraw,
        public AccountWithdrawPix $accountWithdrawPix,
    ) {
    }

    public function getName(): string
    {
        return Events::ACCOUNT_WITHDRAW_PIX_PROCESSED->value;
    }

    public function getCategory(): string
    {
       return EventCategory::WITHDRAW_PIX_PROCESSED->value;
    }

    public function getProperties(): array
    {
        return [
            'account_withdraw_id' => $this->accountWithdraw->id()->value,
            'account_id' => $this->accountWithdraw->accountId()->value,
            'method' => $this->accountWithdraw->method()->value,
            'amount' => $this->accountWithdraw->amount()->value(),
            'scheduled_for' => $this->accountWithdraw->schedule()->date()->format('Y-m-d'),
            'type' => $this->accountWithdrawPix->type()->value(),
            'key' => $this->accountWithdrawPix->key()->value(),
        ];
    }
}