<?php

declare(strict_types=1);

namespace App\Domain\Exception\Handler\Account;

use App\Domain\Exception\PersistenceErrorException;

class AccountWithdrawException extends PersistenceErrorException
{
}
