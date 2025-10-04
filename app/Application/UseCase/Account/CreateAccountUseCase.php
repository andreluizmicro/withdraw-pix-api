<?php

declare(strict_types=1);

namespace App\Application\UseCase\Account;

use App\Application\DTO\Account\CreateAccountInputDTO;
use App\Domain\Entity\Account;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\UuidException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use Exception;

class CreateAccountUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws Exception
     */
    public function execute(CreateAccountInputDTO $input): void
    {
        $account = new Account(
            id: Uuid::random(),
            name: new Name($input->name),
            balance: new Balance(0.0),
        );

        $this->accountRepository->create($account);
    }
}
