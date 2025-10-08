<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Entity;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AccountWithdrawTest extends TestCase
{
    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     */
    public function testShouldCreateAccountWithdrawWithoutSchedule(): void
    {
        $data = [
            'method' => 'PIX',
            'amount' => 100,
        ];

        $accountWithdraw = new AccountWithdraw(
            id: Uuid::random(),
            accountId: Uuid::random(),
            method: WithdrawMethod::tryFrom($data['method']),
            amount: new AmountWithdraw($data['amount']),
            schedule: new Schedule()
        );

        $this->assertInstanceOf(AccountWithdraw::class, $accountWithdraw);
        $this->assertEquals($data['method'], $accountWithdraw->method()->value);
        $this->assertEquals($data['amount'], $accountWithdraw->amount()->value());
    }

    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     */
    public function testShouldCreateWithdrawWithInvalidAmount(): void
    {
        $this->expectException(AmountWithdrawException::class);

        new AccountWithdraw(
            id: Uuid::random(),
            accountId: Uuid::random(),
            method: WithdrawMethod::PIX,
            amount: new AmountWithdraw(-100),
            schedule: new Schedule(new DateTimeImmutable('2025-10-10')),
        );
    }
}
