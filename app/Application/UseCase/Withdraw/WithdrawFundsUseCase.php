<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\Service\Withdraw\WithdrawService;
use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Domain\Event\Withdraw\AccountWithdrawPixErrorEvent;
use App\Domain\Exception\DomainError;
use App\Domain\ValueObject\Uuid;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

final class WithdrawFundsUseCase
{
    public function __construct(
        private readonly WithdrawService $withdrawService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @throws DomainError
     */
    public function execute(CreateWithdrawInputDTO $input): void
    {
        try {
            $result = $this->withdrawService->process($input);

            $this->eventDispatcher->dispatch(
                new AccountWithdrawPixCreatedEvent($result->withdraw, $result->withdrawPix)
            );
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(
                new AccountWithdrawPixErrorEvent(
                    new CreateWithdrawErrorInputDTO(
                        id: Uuid::random()->value,
                        accountId: $input->accountId,
                        method: $input->method,
                        pixType: $input->pixType,
                        pixKey: $input->pixKey,
                        amount: $input->amount,
                        errorReason: $e->getMessage(),
                        scheduledFor: $input->schedule,
                    )
                )
            );

            throw new DomainError($e->getMessage());
        }
    }
}
