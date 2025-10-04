<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use Throwable;

class WithdrawFundsUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @throws DomainError
     */
    public function execute(CreateWithdrawInputDTO $inputDTO): void
    {
        try {
            $account = $this->accountRepository->findById($inputDTO->accountId);

            if ($account === null) {
                throw new AccountNotFoundException();
            }

        } catch (DomainError $exception) {
            throw new DomainError($exception->getMessage());
        }
    }
}
