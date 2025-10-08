<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Service\Withdraw;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Application\Service\Withdraw\ProcessPixScheduledService;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawAggregateRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use App\Infrastructure\DTO\Withdraw\WithdrawAggregateDTO;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProcessPixScheduledServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepository;
    private WithdrawRepositoryInterface $withdrawRepository;
    private WithdrawPixRepositoryInterface $withdrawPixRepository;
    private WithdrawAggregateRepositoryInterface $withdrawAggregateRepository;
    private UnitOfWorkAdapterInterface $unitOfWorkAdapter;
    private WithdrawEmailNotificationService $notificationService;
    private ProcessPixScheduledService $processPixScheduledService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->createMock(AccountRepositoryInterface::class);
        $this->withdrawRepository = $this->createMock(WithdrawRepositoryInterface::class);
        $this->withdrawPixRepository = $this->createMock(WithdrawPixRepositoryInterface::class);
        $this->withdrawAggregateRepository = $this->createMock(WithdrawAggregateRepositoryInterface::class);
        $this->unitOfWorkAdapter = $this->createMock(UnitOfWorkAdapterInterface::class);
        $this->notificationService = $this->createMock(WithdrawEmailNotificationService::class);

        $this->processPixScheduledService = new ProcessPixScheduledService(
            $this->accountRepository,
            $this->withdrawRepository,
            $this->withdrawPixRepository,
            $this->withdrawAggregateRepository,
            $this->unitOfWorkAdapter,
            $this->notificationService
        );
    }

    /**
     * @throws DomainError
     * @throws UuidException
     * @throws \Exception
     */
    public function testShouldProcessPixScheduledSuccessfully(): void
    {
        $this->unitOfWorkAdapter->expects($this->once())
            ->method('begin');

        $inputDTO = new ProcessScheduleInputDTO(
            accountWithdrawId: Uuid::random()->value,
        );

        $withdrawAggregateDTO = new WithdrawAggregateDTO(
            accountId: Uuid::random()->value,
            accountName: 'André Luiz',
            accountBalance: 15000,
            withdrawId: Uuid::random()->value,
            withdrawAmount: 1000,
            withdrawScheduled: true,
            withdrawScheduledFor: '2025-10-08 23:59:59',
            pixId: Uuid::random()->value,
            pixKey: 'andreluiz@gmail.com',
            pixType: 'email',
        );

        $this->withdrawAggregateRepository->expects($this->once())
            ->method('findWithdrawAggregate')
            ->with($inputDTO->accountWithdrawId)
            ->willReturn($withdrawAggregateDTO);

        $account = new Account(
            id: new Uuid($withdrawAggregateDTO->accountId),
            name: new Name('André Luiz'),
            balance: new Balance($attributes['balance'] ?? 1000.0),
            isActive: true,
        );

        $this->accountRepository->expects($this->once())
            ->method('findById')
            ->with($withdrawAggregateDTO->accountId)
            ->willReturn($account);

        $this->accountRepository->expects($this->once())
            ->method('update')
            ->with($account);

        $this->withdrawRepository->expects($this->once())
            ->method('updateScheduledForToday');

        $this->notificationService->expects($this->once())
            ->method('notifySuccess');

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('commit');

        $this->unitOfWorkAdapter->expects($this->never())
            ->method('rollback');

        $this->processPixScheduledService->process($inputDTO);
    }

    /**
     * @throws UuidException
     * @throws ScheduleException
     * @throws AmountWithdrawException
     * @throws WithDrawPixException
     */
    public function testShouldReturnDomainErrorWhenScheduleNotFound(): void
    {
        $this->expectException(DomainError::class);
        $this->expectExceptionMessage('Agendamento não encontrado.');

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('begin');

        $inputDTO = new ProcessScheduleInputDTO(
            accountWithdrawId: Uuid::random()->value,
        );

        $this->withdrawAggregateRepository->expects($this->once())
            ->method('findWithdrawAggregate')
            ->with($inputDTO->accountWithdrawId)
            ->willReturn(null);

        $this->unitOfWorkAdapter->expects($this->never())
            ->method('commit');

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('rollback');

        $this->withdrawRepository->expects($this->once())
            ->method('updateScheduledForToday');

        $accountWithdraw = new AccountWithdraw(
            id: Uuid::random(),
            accountId: Uuid::random(),
            method: WithdrawMethod::PIX,
            amount: new AmountWithdraw(100),
            schedule: new Schedule(new DateTimeImmutable('2025-10-10')),
        );

        $this->withdrawRepository->expects($this->once())
            ->method('findById')
            ->with($inputDTO->accountWithdrawId)
            ->willReturn($accountWithdraw);

        $pixType = new PixType('email');
        $this->withdrawPixRepository->expects($this->once())
            ->method('findByAccountId')
            ->with($accountWithdraw->id()->value)
            ->willReturn(
                new AccountWithDrawPix(
                    id: Uuid::random(),
                    accountWithdrawId: Uuid::random(),
                    type: $pixType,
                    key: new PixKey(
                        type: $pixType,
                        key: 'andreluizsilva@gmail.com'
                    ),
                )
            );

        $this->notificationService->expects($this->once())
            ->method('notifyError');

        $this->processPixScheduledService->process($inputDTO);
    }
}
