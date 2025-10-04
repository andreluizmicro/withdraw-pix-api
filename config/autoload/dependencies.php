<?php

declare(strict_types=1);

use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\Repository\MySQL\Account\DbAccountRepository;
use App\Infrastructure\Repository\MySQL\Withdraw\DbWithdrawRepository;

return [
    // Repository
    AccountRepositoryInterface::class => DbAccountRepository::class,
];

