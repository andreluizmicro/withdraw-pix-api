<?php

declare(strict_types=1);

namespace App\Application\Service\Withdraw;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Exception\DomainError;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawAggregateRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\DTO\Withdraw\UpdateWithdrawProcessErrorDTO;
use Throwable;

class ProcessPixScheduledService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawAggregateRepositoryInterface $withdrawAggregateRepository,
        private readonly UnitOfWorkAdapterInterface $unitOfWorkAdapter,
        private readonly WithdrawEmailNotificationService $notificationService,
    ) {
    }

    /**
     * @throws DomainError
     */
    public function process(ProcessScheduleInputDTO $inputDTO): void
    {
        $this->unitOfWorkAdapter->begin();

        try {
            $aggregate = $this->withdrawAggregateRepository->findWithdrawAggregate($inputDTO->accountWithdrawId);

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

            $this->notificationService->notifySuccess();

            $this->unitOfWorkAdapter->commit();
        } catch (Throwable $exception) {
            $this->unitOfWorkAdapter->rollback();

            $this->withdrawRepository->updateScheduledForToday(
                new UpdateWithdrawProcessErrorDTO(
                    accountWithdrawId: $inputDTO->accountWithdrawId,
                    error: true,
                    errorReason: $exception->getMessage(),
                    done: false
                )
            );

            // notificar

            throw new DomainError($exception->getMessage());
        }
    }
}
