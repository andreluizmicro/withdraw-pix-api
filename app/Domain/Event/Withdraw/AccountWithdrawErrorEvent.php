<?php

declare(strict_types=1);

namespace App\Domain\Event\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;

class AccountWithdrawErrorEvent  extends AccountWithdrawEvent
{
    public function __construct(
      private readonly CreateWithdrawErrorInputDTO $inputDTO,
    ) {
    }

    public function getName(): string
    {
       return Events::ACCOUNT_WITHDRAW_ERROR->value;
    }

    public function getCategory(): string
    {
        return EventCategory::WITHDRAW_ERROR->value;
    }

    public function getProperties(): array
    {
        return $this->inputDTO->toArray();
    }
}
