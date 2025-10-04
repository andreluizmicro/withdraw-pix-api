<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use App\Domain\Event\Withdraw\AccountWithdrawEvent;

class AccountWithdrawErrorEvent extends AccountWithdrawEvent
{
    public function getName(): string
    {
        return 'account_withdraw_error';
    }

    public function getCategory(): string
    {
        return EventCategory::WITHDRAW_ERROR->value;
    }
}
