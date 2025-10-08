<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Service\Notification;

use App\Application\DTO\Notification\WithdrawNotificationInputDTO;
use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Domain\Entity\Account;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Notification\EmailNotificationInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class WithdrawEmailNotificationServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepository;
    private WithdrawRepositoryInterface $withdrawRepository;
    private WithdrawPixRepositoryInterface $withdrawPixRepository;

    private EmailNotificationInterface $emailNotification;

    private WithdrawEmailNotificationService $withdrawEmailNotificationService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->createMock(AccountRepositoryInterface::class);
        $this->withdrawRepository = $this->createMock(WithdrawRepositoryInterface::class);
        $this->withdrawPixRepository = $this->createMock(WithdrawPixRepositoryInterface::class);
        $this->emailNotification = $this->createMock(EmailNotificationInterface::class);

        $this->withdrawEmailNotificationService = new WithdrawEmailNotificationService(
            $this->accountRepository,
            $this->withdrawRepository,
            $this->withdrawPixRepository,
            $this->emailNotification,
        );
    }

    /**
     * @throws UuidException
     * @throws WithDrawPixException
     * @throws BalanceException
     * @throws \Exception
     */
    public function testShouldSendNotificationError(): void
    {
        $type = (new PixType('email'));

        $withdrawInputDto = new WithdrawNotificationInputDTO(
            accountId: Uuid::random()->value,
            accountWithdrawId: Uuid::random()->value,
            accountWithdrawPixId: Uuid::random()->value,
            pixType: $type->value(),
            pixKey: 'andreluiz@gmail.com',
        );

        $this->accountRepository->expects($this->once())
            ->method('findById')
            ->with($withdrawInputDto->accountId)
            ->willReturn(new Account(
                id: Uuid::random(),
                name: new Name('Andre Luiz'),
                balance: new Balance(1500),
            ));

        $this->emailNotification->expects($this->once())
            ->method('sendEmail');

        $this->withdrawEmailNotificationService->notifyError($withdrawInputDto);}
}
