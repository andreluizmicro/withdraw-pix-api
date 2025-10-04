<?php

declare(strict_types=1);

namespace App\Domain\Event\Withdraw;

use App\Domain\Enum\EventCategory;

class AccountWithdrawCreatedEvent extends AccountWithdrawEvent
{
    public function getName(): string
    {
        return 'account_withdraw_created';
    }

    public function getCategory(): string
    {
        return EventCategory::WITHDRAWN_CREATION->value;
    }
}
