<?php

declare(strict_types=1);

namespace App\Domain\Repository\Account;

use App\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function create(Account $account): void;
}