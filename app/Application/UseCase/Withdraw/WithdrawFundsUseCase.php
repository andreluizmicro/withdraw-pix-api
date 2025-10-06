<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Event\Withdraw\AccountWithdrawCreatedEvent;
use App\Domain\Event\Withdraw\AccountWithdrawErrorEvent;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class WithdrawFundsUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UnitOfWorkAdapterInterface $unitOfWorkAdapter,
    ) {
    }

    /**
     * @throws DomainError
     */
    public function execute(CreateWithdrawInputDTO $inputDTO): void
    {
        $this->unitOfWorkAdapter->begin();

        try {
            $account = $this->accountRepository->findById($inputDTO->accountId);

            if ($account === null) {
                throw new AccountNotFoundException();
            }

            $accountWithdraw = new AccountWithdraw(
                id: Uuid::random(),
                accountId: $account->id(),
                method: WithdrawMethod::tryFrom($inputDTO->method),
                amount: New AmountWithdraw($inputDTO->amount),
                schedule: new Schedule(
                    date: is_null($inputDTO->schedule) ? null : new DateTimeImmutable($inputDTO->schedule),
                ),
            );

            $account->subtract($accountWithdraw->amount()->value());

            $this->accountRepository->update($account);

            $this->eventDispatcher->dispatch(new AccountWithdrawCreatedEvent($accountWithdraw));

            $this->unitOfWorkAdapter->commit();
        } catch (Throwable $exception) {
            $this->eventDispatcher->dispatch(new AccountWithdrawErrorEvent(
                new CreateWithdrawErrorInputDTO(
                    id: Uuid::random()->value,
                    accountId: $inputDTO->accountId,
                    method: $inputDTO->method,
                    amount: $inputDTO->amount,
                    errorReason: $exception->getMessage(),
                    scheduledFor: is_null($inputDTO->schedule) ? null : $inputDTO->schedule,
                ),
            ));

            $this->unitOfWorkAdapter->rollback();

            throw new DomainError($exception->getMessage());
        }
    }
}
