<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Service\Notification;

use App\Application\DTO\Notification\WithdrawNotificationInputDTO;
use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Notification\EmailNotificationInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\Exception as MockException;
use PHPUnit\Framework\TestCase;

class WithdrawEmailNotificationServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepository;
    private WithdrawRepositoryInterface $withdrawRepository;
    private WithdrawPixRepositoryInterface $withdrawPixRepository;

    private EmailNotificationInterface $emailNotification;

    private WithdrawEmailNotificationService $withdrawEmailNotificationService;

    /**
     * @throws MockException
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
     * @throws BalanceException
     * @throws Exception
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

        $this->withdrawEmailNotificationService->notifyError($withdrawInputDto);
    }

    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     * @throws NameException
     * @throws BalanceException
     * @throws Exception
     */
    public function testShouldSendNotificationSuccess(): void
    {
        $type = (new PixType('email'));

        $withdrawInputDto = new WithdrawNotificationInputDTO(
            accountId: Uuid::random()->value,
            accountWithdrawId: Uuid::random()->value,
            accountWithdrawPixId: Uuid::random()->value,
            pixType: $type->value(),
            pixKey: 'andreluiz@gmail.com',
        );

        $pixType = new PixType('email');

        $withdrawPix =  new AccountWithDrawPix(
            id: Uuid::random(),
            accountWithdrawId: Uuid::random(),
            type: $pixType,
            key: new PixKey(
                type: $pixType,
                key: 'andreluizsilva@gmail.com'
            ),
        );

        $accountWithdraw = new AccountWithdraw(
            id: Uuid::random(),
            accountId: Uuid::random(),
            method: WithdrawMethod::PIX,
            amount: new AmountWithdraw(100),
            schedule: new Schedule(new DateTimeImmutable('2025-10-10')),
        );

        $account = new Account(
            id: Uuid::random(),
            name: new Name( 'John Doe'),
            balance: new Balance( 1000.0),
        );

        $this->withdrawPixRepository->expects($this->once())
            ->method('findById')
            ->with($withdrawInputDto->accountWithdrawPixId)
            ->willReturn($withdrawPix);

        $this->withdrawRepository->expects($this->once())
            ->method('findById')
            ->with($withdrawPix->accountWithdrawId()->value)
            ->willReturn($accountWithdraw);

        $this->accountRepository->expects($this->once())
            ->method('findById')
            ->with($accountWithdraw->accountId()->value)
            ->willReturn($account);

        $this->withdrawEmailNotificationService->notifySuccess($withdrawInputDto);
    }
}
