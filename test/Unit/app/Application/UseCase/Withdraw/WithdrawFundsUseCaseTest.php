<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\DTO\Withdraw\WithdrawResult;
use App\Application\Service\Withdraw\WithdrawService;
use App\Application\UseCase\Withdraw\WithdrawFundsUseCase;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\DomainError;
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
use Psr\EventDispatcher\EventDispatcherInterface;

class WithdrawFundsUseCaseTest extends TestCase
{
    private WithdrawService $withdrawService;
    private EventDispatcherInterface $eventDispatcher;

    private WithdrawFundsUseCase $withdrawFundsUseCase;
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withdrawService = $this->createMock(WithdrawService::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->withdrawFundsUseCase = new WithdrawFundsUseCase(
            $this->withdrawService,
            $this->eventDispatcher
        );
    }

    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     * @throws WithDrawPixException
     * @throws DomainError
     */
    public function testShouldProcessWithdrawFunds(): void
    {
        $inputDto = new CreateWithdrawInputDTO(
            accountId: Uuid::random()->value,
            method: 'PIX',
            pixType: 'email',
            pixKey: 'andreluiz@gmail.com',
            amount: 15000,
        );

        $pixType = new PixType('email');

        $withdrawResult = new WithdrawResult(
            withdraw: new AccountWithdraw(
                id: Uuid::random(),
                accountId: Uuid::random(),
                method: WithdrawMethod::PIX,
                amount: new AmountWithdraw(100),
                schedule: new Schedule(new DateTimeImmutable('2025-10-10')),
            ),
            withdrawPix: new AccountWithDrawPix(
                id: Uuid::random(),
                accountWithdrawId: Uuid::random(),
                type: $pixType,
                key: new PixKey(
                    type: $pixType,
                    key: 'andreluizsilva@gmail.com'
                ),
            ),
        );

        $this->withdrawService->expects($this->once())
            ->method('process')
            ->with($inputDto)
            ->willReturn($withdrawResult);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $this->withdrawFundsUseCase->execute($inputDto);
    }

    /**
     * @throws UuidException
     * @throws DomainError
     */
    public function testShouldReturnInvalidAmountException(): void
    {
        $this->expectException(DomainError::class);

        $inputDto = new CreateWithdrawInputDTO(
            accountId: Uuid::random()->value,
            method: 'PIX',
            pixType: 'email',
            pixKey: 'andreluiz@gmail.com',
            amount: -15000,
        );

        $this->withdrawService->expects($this->once())
            ->method('process')
            ->with($inputDto)
            ->willThrowException(new AmountWithdrawException());

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $this->withdrawFundsUseCase->execute($inputDto);
    }
}
