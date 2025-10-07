<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithdrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Domain\Event\Withdraw\AccountWithdrawPixErrorEvent;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithdrawException;
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

final class WithdrawFundsUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UnitOfWorkAdapterInterface $unitOfWork,
    ) {}

    /**
     * @throws DomainError
     */
    public function execute(CreateWithdrawInputDTO $input): void
    {
        $this->unitOfWork->begin();

        try {
            $account = $this->getAccountOrFail($input->accountId);
            $withdraw = $this->buildWithdraw($input, $account);
            $withdrawPix = $this->buildWithdrawPix($withdraw, $input);

            $this->processImmediateWithdraw($withdraw, $account);

            $this->persistEntities($withdraw, $withdrawPix);
            $this->dispatchCreatedEvent($withdraw, $withdrawPix);

            $this->unitOfWork->commit();
        } catch (Throwable $e) {
            $this->handleFailure($e, $input);
            throw new DomainError($e->getMessage());
        }
    }

    private function getAccountOrFail(string $accountId): Account
    {
        $account = $this->accountRepository->findById($accountId);

        if ($account === null) {
            throw new AccountNotFoundException();
        }

        return $account;
    }

    /**
     * @throws AmountWithdrawException
     * @throws UuidException
     * @throws ScheduleException
     */
    private function buildWithdraw(CreateWithdrawInputDTO $input, Account $account): AccountWithdraw
    {
        return new AccountWithdraw(
            id: Uuid::random(),
            accountId: $account->id(),
            method: WithdrawMethod::tryFrom($input->method),
            amount: new AmountWithdraw($input->amount),
            schedule: new Schedule(
                date: $input->schedule ? new DateTimeImmutable($input->schedule) : null,
            ),
        );
    }

    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    private function buildWithdrawPix(AccountWithdraw $withdraw, CreateWithdrawInputDTO $input): AccountWithdrawPix
    {
        $pixType = new PixType($input->pixType);

        return new AccountWithdrawPix(
            id: Uuid::random(),
            accountWithdrawId: $withdraw->id(),
            type: $pixType,
            key: new PixKey($pixType, $input->pixKey),
        );
    }

    /**
     * @throws WithdrawException
     */
    private function processImmediateWithdraw(AccountWithdraw $withdraw, Account $account): void
    {
        if (!$withdraw->schedule()->scheduled()) {
            $account->subtract($withdraw->amount()->value());
            $this->accountRepository->update($account);
        }
    }

    private function persistEntities(AccountWithdraw $withdraw, AccountWithdrawPix $withdrawPix): void
    {
        $this->withdrawRepository->create($withdraw);
        $this->withdrawPixRepository->create($withdrawPix);
    }

    private function dispatchCreatedEvent(AccountWithdraw $withdraw, AccountWithdrawPix $withdrawPix): void
    {
        $this->eventDispatcher->dispatch(new AccountWithdrawPixCreatedEvent($withdraw, $withdrawPix));
    }

    /**
     * @throws UuidException
     */
    private function handleFailure(Throwable $e, CreateWithdrawInputDTO $input): void
    {
        $this->unitOfWork->rollback();

        $error = new CreateWithdrawErrorInputDTO(
            id: Uuid::random()->value,
            accountId: $input->accountId,
            method: $input->method,
            pixType: $input->pixType,
            pixKey: $input->pixKey,
            amount: $input->amount,
            errorReason: $e->getMessage(),
            scheduledFor: $input->schedule,
        );

        $this->withdrawRepository->createError($error);
        $this->withdrawPixRepository->createError($error);
        $this->eventDispatcher->dispatch(new AccountWithdrawPixErrorEvent($error));
    }
}
