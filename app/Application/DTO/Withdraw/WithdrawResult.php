<?php

declare(strict_types=1);

namespace App\Application\DTO\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;

final class WithdrawResult
{
    public function __construct(
        public readonly AccountWithdraw $withdraw,
        public readonly AccountWithdrawPix $withdrawPix,
    ) {}
}
