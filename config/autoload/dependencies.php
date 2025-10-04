<?php

declare(strict_types=1);

use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawAttemptRepositoryInterface;
use App\Infrastructure\Repository\MySQL\Account\DbAccountRepository;
use App\Infrastructure\Repository\MySQL\Withdraw\DbWithdrawAttemptRepository;

return [
    // Repository
    AccountRepositoryInterface::class => DbAccountRepository::class,
    WithdrawAttemptRepositoryInterface::class => DbWithdrawAttemptRepository::class
];

