<?php

declare(strict_types=1);

namespace App\Domain\Repository\Withdraw;

use App\Domain\Entity\AccountWithdraw;

interface WithdrawAttemptRepositoryInterface
{
    public function createLogFailedAttempt(AccountWithdraw $accountWithdraw): void;
}