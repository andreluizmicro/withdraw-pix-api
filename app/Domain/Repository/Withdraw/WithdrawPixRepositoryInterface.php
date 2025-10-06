<?php

declare(strict_types=1);

namespace App\Domain\Repository\Withdraw;

use App\Domain\Entity\AccountWithDrawPix;

interface WithdrawPixRepositoryInterface
{
    public function create(AccountWithDrawPix $accountWithdrawPix): void;
}
