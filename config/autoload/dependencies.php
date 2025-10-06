<?php

declare(strict_types=1);

use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawAttemptRepositoryInterface;
use App\Infrastructure\Adapter\UnitOfWorkAdapter;
use App\Infrastructure\Repository\MySQL\Account\DbAccountRepository;
use App\Infrastructure\Repository\MySQL\Withdraw\DbWithdrawAttemptRepository;

return [
    // Repository
    AccountRepositoryInterface::class => DbAccountRepository::class,
    WithdrawAttemptRepositoryInterface::class => DbWithdrawAttemptRepository::class,

    // Adapter
    UnitOfWorkAdapterInterface::class => UnitOfWorkAdapter::class,
];

