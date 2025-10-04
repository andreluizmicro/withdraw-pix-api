<?php

declare(strict_types=1);

namespace App\Domain\Repository\Account;

use App\Domain\Entity\Account;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\Handler\Account\CreateAccountException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use Exception;

interface AccountRepositoryInterface
{
    /**
     * @throws CreateAccountException
     */
    public function create(Account $account): void;

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws Exception
     */
    public function findById(string $id): ?Account;
}
