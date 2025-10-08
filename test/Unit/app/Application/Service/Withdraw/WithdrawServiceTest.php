<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Service\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\DTO\Withdraw\WithdrawResult;
use App\Application\Service\Withdraw\WithdrawService;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Entity\Account;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithdrawException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use Exception;
use PHPUnit\Framework\MockObject\Exception as MockException;
use PHPUnit\Framework\TestCase;
use Throwable;

class WithdrawServiceTest extends TestCase
{
    private AccountRepositoryInterface $accountRepository;
    private WithdrawRepositoryInterface $withdrawRepository;
    private WithdrawPixRepositoryInterface $withdrawPixRepository;
    private UnitOfWorkAdapterInterface $unitOfWorkAdapter;
    private WithdrawService $withdrawService;


    /**
     * @throws MockException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->createMock(AccountRepositoryInterface::class);
        $this->withdrawRepository = $this->createMock(WithdrawRepositoryInterface::class);
        $this->withdrawPixRepository = $this->createMock(WithdrawPixRepositoryInterface::class);
        $this->unitOfWorkAdapter = $this->createMock(UnitOfWorkAdapterInterface::class);

        $this->withdrawService = new WithdrawService(
            $this->accountRepository,
            $this->withdrawRepository,
            $this->withdrawPixRepository,
            $this->unitOfWorkAdapter,
        );
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws MockException
     * @throws Exception
     * @throws Throwable
     */
    public function testShouldProcessWithdrawWithoutSchedule(): void
    {
        $this->unitOfWorkAdapter->expects($this->once())
            ->method('begin');

        $inputDTO = new CreateWithdrawInputDTO(
            accountId: 'c985ee25-5605-4c6b-a8d2-478edf894136',
            method: 'PIX',
            pixType: 'email',
            pixKey: 'andreluiz@gmail.com',
            amount: 150,
        );

        $account = new Account(
            id: Uuid::random(),
            name: new Name('John Doe'),
            balance: new Balance(1000.0),
        );

        $this->accountRepository->expects($this->once())
            ->method('findById')
            ->with($inputDTO->accountId)
            ->willReturn($account);

        $this->accountRepository->expects($this->once())
            ->method('update')
            ->with($account);

        $this->withdrawRepository->expects($this->once())
            ->method('create');

        $this->withdrawPixRepository->expects($this->once())
            ->method('create');

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('commit');

        $withdrawResult = $this->withdrawService->process($inputDTO);

        $this->assertInstanceOf(WithdrawResult::class, $withdrawResult);

        $this->assertEquals($account->id()->value, $withdrawResult->withdraw->accountId()->value);
        $this->assertEquals('PIX', $withdrawResult->withdraw->method()->value);
        $this->assertEquals('email', $withdrawResult->withdrawPix->type()->value());
        $this->assertEquals('andreluiz@gmail.com', $withdrawResult->withdrawPix->key()->value());
    }

    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws NameException
     * @throws ScheduleException
     * @throws  Throwable
     * @throws WithdrawException
     * @throws BalanceException
     * @throws WithDrawPixException
     */
    public function testShouldReturnExceptionWhenAccountNotFound(): void
    {
        $this->expectException(AccountNotFoundException::class);
        $this->expectExceptionMessage('Account not found');

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('begin');

        $this->accountRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->unitOfWorkAdapter->expects($this->once())
            ->method('rollback');

        $this->withdrawService->process(
            input: new CreateWithdrawInputDTO(
                accountId: 'c985ee25-5605-4c6b-a8d2-478edf894136',
                method: 'PIX',
                pixType: 'email',
                pixKey: 'andreluiz@gmail.com',
                amount: 1500,
            ),
        );
    }
}
