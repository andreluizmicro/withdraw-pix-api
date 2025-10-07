<?php

declare(strict_types=1);

namespace App\Application\UseCase\Schedule;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use Throwable;

class ProcessSchedulePixUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly UnitOfWorkAdapterInterface $unitOfWorkAdapter,
    ) {
    }

    /**
     * @throws DomainError
     */
    public function execute(ProcessScheduleInputDTO $inputDTO): void
    {
        $this->unitOfWorkAdapter->begin();

        try {
            $accountWithdraw = $this->withdrawRepository->findById($inputDTO->accountWithdrawId);

            if (empty($accountWithdraw)) {
                throw new AccountNotFoundException();
            }

            $accountWithDrawPix = $this->withdrawPixRepository->findByAccountId($accountWithdraw->id()->value);

            if (empty($accountWithDrawPix)) {
                throw new AccountNotFoundException();
            }

            $account = $this->accountRepository->findById($accountWithdraw->accountId()->value);

            if (empty($account)) {
                throw new AccountNotFoundException();
            }

            $account->subtract($accountWithdraw->amount()->value());

            $this->accountRepository->update($account);

            $this->withdrawRepository->updateScheduledForToday($accountWithdraw->id()->value, true);

            $this->unitOfWorkAdapter->commit();
        } catch (Throwable $exception) {
            $this->unitOfWorkAdapter->rollback();

            throw new DomainError($exception->getMessage());
        }
    }
}
