<?php

declare(strict_types=1);

use App\Application\Service\NotificationService;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Notification\NotificationInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\Adapter\UnitOfWorkAdapter;
use App\Infrastructure\Repository\MySQL\Account\DbAccountRepository;
use App\Infrastructure\Repository\MySQL\Withdraw\DbWithdrawPixRepository;
use App\Infrastructure\Repository\MySQL\Withdraw\DbWithdrawRepository;

return [
    // Repository
    AccountRepositoryInterface::class => DbAccountRepository::class,
    WithdrawRepositoryInterface::class => DbWithdrawRepository::class,
    WithdrawPixRepositoryInterface::class => DbWithdrawPixRepository::class,

    // Adapter
    UnitOfWorkAdapterInterface::class => UnitOfWorkAdapter::class,

    // Service
    NotificationInterface::class => NotificationService::class
];

