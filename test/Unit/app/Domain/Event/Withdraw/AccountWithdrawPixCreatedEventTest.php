<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Event\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;
use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AccountWithdrawPixCreatedEventTest extends TestCase
{
    private AccountWithdraw $accountWithdraw;
    private AccountWithdrawPix $accountWithdrawPix;

    private AccountWithdrawPixCreatedEvent $event;


    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountWithdraw = $this->createMock(AccountWithdraw::class);
        $this->accountWithdrawPix = $this->createMock(AccountWithdrawPix::class);

        $this->event = new AccountWithdrawPixCreatedEvent(
            $this->accountWithdraw,
            $this->accountWithdrawPix,
        );
    }

    /**
     * @throws ScheduleException
     * @throws UuidException
     */
    public function testShouldCreateWithdrawPixProcessedEvent(): void
    {
        $id = Uuid::random();
        $schedule = new Schedule((new DateTimeImmutable(''))->modify('+1 day'));

        $this->accountWithdrawPix->expects(self::once())
            ->method('id')
            ->willReturn($id);

        $this->accountWithdraw->expects(self::once())
            ->method('schedule')
            ->willReturn($schedule);

        $this->assertInstanceOf(AccountWithdrawPixCreatedEvent::class, $this->event);
        $this->assertEquals(Events::ACCOUNT_WITHDRAW_CREATED->value, $this->event->getName());
        $this->assertEquals(EventCategory::WITHDRAWN_CREATION->value, $this->event->getCategory());

        $this->assertEquals([
            'event_name' => $this->event->getName(),
            'account_withdraw_pix_id' => $id->value,
            'scheduled' => true,
        ], $this->event->getProperties());
    }
}
