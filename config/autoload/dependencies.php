<?php

declare(strict_types=1);

use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Infrastructure\Repository\Account\AccountRepository;

return [
    AccountRepositoryInterface::class => AccountRepository::class,
];

