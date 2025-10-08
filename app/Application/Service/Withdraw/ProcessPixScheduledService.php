<?php

declare(strict_types=1);

namespace App\Application\Service\Withdraw;

use App\Application\DTO\Notification\WithdrawNotificationInputDTO;
use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithdrawException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawAggregateRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\DTO\Withdraw\UpdateWithdrawProcessErrorDTO;
use Throwable;

class ProcessPixScheduledService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly WithdrawAggregateRepositoryInterface $withdrawAggregateRepository,
        private readonly UnitOfWorkAdapterInterface $unitOfWorkAdapter,
        private readonly WithdrawEmailNotificationService $notificationService,
    ) {}

    /**
     * @throws DomainError
     */
    public function process(ProcessScheduleInputDTO $inputDTO): void
    {
        $this->unitOfWorkAdapter->begin();

        try {
            $aggregate = $this->withdrawAggregateRepository->findWithdrawAggregate($inputDTO->accountWithdrawId);

            if ($aggregate === null) {
                throw new DomainError('Agendamento não encontrado.');
            }

            $this->processWithdrawal($aggregate);

            $this->notificationService->notifySuccess(
                new WithdrawNotificationInputDTO(
                    accountWithdrawPixId: $aggregate->pixId,
                )
            );

            $this->unitOfWorkAdapter->commit();
        } catch (Throwable $exception) {
            $this->unitOfWorkAdapter->rollback();

            $this->handleFailure($inputDTO, $exception);

            throw new DomainError($exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws WithdrawException
     * @throws BalanceException
     */
    private function processWithdrawal($aggregate): void
    {
        $account = $this->accountRepository->findById($aggregate->accountId);

        $account->subtract($aggregate->withdrawAmount);
        $this->accountRepository->update($account);

        $this->withdrawRepository->updateScheduledForToday(
            new UpdateWithdrawProcessErrorDTO(
                accountWithdrawId: $aggregate->withdrawId,
                error: false,
                errorReason: null,
                done: true
            )
        );
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     */
    private function handleFailure(ProcessScheduleInputDTO $inputDTO, Throwable $exception): void
    {
        $this->withdrawRepository->updateScheduledForToday(
            new UpdateWithdrawProcessErrorDTO(
                accountWithdrawId: $inputDTO->accountWithdrawId,
                error: true,
                errorReason: $exception->getMessage(),
                done: false
            )
        );

        $accountWithdraw = $this->withdrawRepository->findById($inputDTO->accountWithdrawId);
        $accountWithdrawPix = $this->withdrawPixRepository->findByAccountId($accountWithdraw->id()->value);

        $this->notificationService->notifyError(
            new WithdrawNotificationInputDTO(
                accountId: $accountWithdraw->accountId()->value,
                accountWithdrawId: $inputDTO->accountWithdrawId,
                pixType: $accountWithdrawPix->type()->value(),
                pixKey: $accountWithdrawPix->key()->value(),
                amount: $accountWithdraw->amount()->value(),
            )
        );
    }
}
