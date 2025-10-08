<?php

declare(strict_types=1);

namespace App\Domain\Repository\Withdraw;

use App\Infrastructure\DTO\Withdraw\WithdrawAggregateDTO;

interface WithdrawAggregateRepositoryInterface
{
    public function findWithdrawAggregate(string $accountWithdrawId): ?WithdrawAggregateDTO;
}
