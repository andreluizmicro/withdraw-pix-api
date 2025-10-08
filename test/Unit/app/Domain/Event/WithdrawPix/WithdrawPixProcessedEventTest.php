<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Event\WithdrawPix;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Event\WithdrawPix\WithdrawPixProcessedEvent;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class WithdrawPixProcessedEventTest extends TestCase
{
    private AccountWithdraw $accountWithdraw;
    private AccountWithdrawPix $accountWithdrawPix;

    private WithdrawPixProcessedEvent $event;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountWithdraw = $this->createMock(AccountWithdraw::class);
        $this->accountWithdrawPix = $this->createMock(AccountWithdrawPix::class);

        $this->event = new WithdrawPixProcessedEvent(
            $this->accountWithdraw,
            $this->accountWithdrawPix,
        );
    }

    /**
     * @throws UuidException
     * @throws Exception
     * @throws ScheduleException
     * @throws AmountWithdrawException
     * @throws WithDrawPixException
     */
    public function testShouldCreateWithdrawPixProcessedEvent(): void
    {
        $id = Uuid::random();
        $accountId = Uuid::random();
        $withdrawAmount =new AmountWithdraw(100);
        $schedule = new Schedule((new DateTimeImmutable(''))->modify('+1 day'));
        $pixType = new PixType('email');
        $pixKey = new PixKey($pixType, 'andreluiz@gmail.com');

        $this->accountWithdraw->expects(self::once())
            ->method('id')
            ->willReturn($id);

        $this->accountWithdraw->expects(self::once())
            ->method('accountId')
            ->willReturn($accountId);

        $this->accountWithdraw->expects(self::once())
            ->method('method')
            ->willReturn(WithdrawMethod::PIX);

        $this->accountWithdraw->expects(self::once())
            ->method('amount')
            ->willReturn($withdrawAmount);

        $this->accountWithdraw->expects(self::once())
            ->method('schedule')
            ->willReturn($schedule);

        $this->accountWithdrawPix->expects(self::once())
            ->method('type')
            ->willReturn($pixType);

        $this->accountWithdrawPix->expects(self::once())
            ->method('key')
            ->willReturn($pixKey);

        $this->assertInstanceOf(WithdrawPixProcessedEvent::class, $this->event);
        $this->assertEquals(Events::ACCOUNT_WITHDRAW_PIX_PROCESSED->value, $this->event->getName());
        $this->assertEquals(EventCategory::WITHDRAW_PIX_PROCESSED->value, $this->event->getCategory());

        $this->assertEquals([
            'account_withdraw_id' => $id->value,
            'account_id' => $accountId->value,
            'method' => WithdrawMethod::PIX->value,
            'amount' => $withdrawAmount->value(),
            'scheduled_for' => (new DateTimeImmutable(''))->modify('+1 day')->format('Y-m-d'),
            'type' => $pixType->value(),
            'key' => $pixKey->value(),
        ], $this->event->getProperties());
    }
}
