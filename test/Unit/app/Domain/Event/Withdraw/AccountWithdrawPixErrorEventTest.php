<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Event\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Domain\Enum\EventCategory;
use App\Domain\Enum\Events;
use App\Domain\Event\Withdraw\AccountWithdrawPixErrorEvent;
use PHPUnit\Framework\TestCase;

class AccountWithdrawPixErrorEventTest extends TestCase
{
    private CreateWithdrawErrorInputDTO $inputDTO;

    private AccountWithdrawPixErrorEvent $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputDTO = $this->createMock(CreateWithdrawErrorInputDTO::class);

        $this->event = new AccountWithdrawPixErrorEvent(
            $this->inputDTO
        );
    }

    public function testShouldCreateAccountWithdrawPixErrorEvent(): void
    {
        $this->assertInstanceOf(AccountWithdrawPixErrorEvent::class, $this->event);
        $this->assertEquals(Events::ACCOUNT_WITHDRAW_ERROR->value, $this->event->getName());
        $this->assertEquals(EventCategory::WITHDRAW_ERROR->value, $this->event->getCategory());
        $this->assertEquals($this->inputDTO->toArray(), $this->event->getProperties());
    }
}
