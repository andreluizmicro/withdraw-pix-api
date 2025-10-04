<?php

declare(strict_types=1);

use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\WithdrawPix\WithdrawPixRepositoryInterface;
use App\Infrastructure\Repository\MySQL\Account\DbAccountRepository;
use App\Infrastructure\Repository\MySQL\WithdrawPix\DbWithdrawPixRepository;

return [
    // Repository
    AccountRepositoryInterface::class => DbAccountRepository::class,
    WithdrawPixRepositoryInterface::class => DbWithdrawPixRepository::class,
];

