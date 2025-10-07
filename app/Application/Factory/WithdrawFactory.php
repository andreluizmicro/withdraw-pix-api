<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class WithdrawFactory
{
    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     */
    public static function create(CreateWithdrawInputDTO $input, Account $account): AccountWithdraw
    {
        return new AccountWithdraw(
            id: Uuid::random(),
            accountId: $account->id(),
            method: WithdrawMethod::tryFrom($input->method),
            amount: new AmountWithdraw($input->amount),
            schedule: new Schedule(
                date: $input->schedule ? new DateTimeImmutable($input->schedule) : null
            ),
        );
    }
}
