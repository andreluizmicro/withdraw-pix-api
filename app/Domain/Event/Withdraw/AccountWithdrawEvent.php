<?php

declare(strict_types=1);

namespace App\Domain\Event\Withdraw;

use App\Domain\Event\EventInterface;

abstract class AccountWithdrawEvent implements EventInterface
{
    abstract public function getProperties(): array;
}
