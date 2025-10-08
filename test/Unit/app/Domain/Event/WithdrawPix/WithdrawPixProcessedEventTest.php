<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Event\WithdrawPix;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Event\WithdrawPix\WithdrawPixProcessedEvent;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WithdrawPixProcessedEventTest extends TestCase
{
    private AccountWithdraw|MockObject $accountWithdraw;
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

    public function testShouldCreateWithdrawPixProcessedEvent(): void
    {
        $this->assertInstanceOf(WithdrawPixProcessedEvent::class, $this->event);
    }
}