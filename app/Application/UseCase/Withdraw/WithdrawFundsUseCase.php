<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Domain\Event\Withdraw\AccountWithdrawPixErrorEvent;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class WithdrawFundsUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
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

            $accountWithdraw = $this->createWithdraw($inputDTO, $account);

            $accountWithDrawPix = $this->CreateWithdrawPix($accountWithdraw, $inputDTO);

            if ($accountWithdraw->schedule()->scheduled() === false) {
                $account->subtract($accountWithdraw->amount()->value());
                $this->accountRepository->update($account);
            }

            $this->withdrawRepository->create($accountWithdraw);

            $this->withdrawPixRepository->create($accountWithDrawPix);

            $this->eventDispatcher->dispatch(
                new AccountWithdrawPixCreatedEvent(
                    $accountWithdraw,
                    $accountWithDrawPix,
                ));

            $this->unitOfWorkAdapter->commit();
        } catch (Throwable $exception) {
            $this->eventDispatcher->dispatch(new AccountWithdrawPixErrorEvent(
                new CreateWithdrawErrorInputDTO(
                    id: Uuid::random()->value,
                    accountId: $inputDTO->accountId,
                    method: $inputDTO->method,
                    pixType: $inputDTO->pixType,
                    pixKey: $inputDTO->pixKey,
                    amount: $inputDTO->amount,
                    errorReason: $exception->getMessage(),
                    scheduledFor: is_null($inputDTO->schedule) ? null : $inputDTO->schedule,
                ),
            ));

            $this->unitOfWorkAdapter->rollback();

            throw new DomainError($exception->getMessage());
        }
    }

    /**
     * @throws AmountWithdrawException
     * @throws UuidException
     * @throws ScheduleException
     */
    private function createWithdraw(CreateWithdrawInputDTO $inputDTO, Account $account): AccountWithdraw
    {
        return new AccountWithdraw(
            id: Uuid::random(),
            accountId: $account->id(),
            method: WithdrawMethod::tryFrom($inputDTO->method),
            amount: New AmountWithdraw($inputDTO->amount),
            schedule: new Schedule(
                date: is_null($inputDTO->schedule) ? null : new DateTimeImmutable($inputDTO->schedule),
            ),
        );
    }

    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    private function createWithdrawPix(
        AccountWithdraw $accountWithdraw,
        CreateWithdrawInputDTO $inputDTO
    ): AccountWithDrawPix {
        $pixType = new PixType($inputDTO->pixType);

        return new AccountWithdrawPix(
            id: Uuid::random(),
            accountWithdrawId: $accountWithdraw->id(),
            type: $pixType,
            key: new PixKey(
                type: $pixType,
                key: $inputDTO->pixKey,
            ),
        );
    }
}
