<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\UseCase\Account;

use App\Application\DTO\Account\CreateAccountInputDTO;
use App\Application\UseCase\Account\CreateAccountUseCase;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\UuidException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateAccountUseCaseTest extends TestCase
{
    private AccountRepositoryInterface $accountRepository;

    private CreateAccountUseCase  $createAccountUseCase;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = $this->createMock(AccountRepositoryInterface::class);

        $this->createAccountUseCase = new CreateAccountUseCase($this->accountRepository);
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     */
    public function testShouldCreateAccountUseCaseExecute(): void
    {
        $this->accountRepository->expects($this->once())
            ->method('create');

        $this->createAccountUseCase->execute(
            new CreateAccountInputDTO('André Luiz')
        );
    }
}
