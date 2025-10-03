<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Account;

use App\Domain\Entity\Account;
use App\Domain\Repository\Account\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{

    public function create(Account $account): void
    {

    }
}
