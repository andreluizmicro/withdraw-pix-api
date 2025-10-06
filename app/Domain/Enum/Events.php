<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum Events: string
{
    case ACCOUNT_WITHDRAW_CREATED = 'account_withdraw_created';
    case ACCOUNT_WITHDRAW_ERROR = 'account_withdraw_error';
}
