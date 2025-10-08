<?php

declare(strict_types=1);

namespace App\Application\Service\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\DTO\Withdraw\WithdrawResult;
use App\Application\Factory\WithdrawFactory;
use App\Application\Factory\WithdrawPixFactory;
use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithdrawException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use Throwable;

class WithdrawService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly UnitOfWorkAdapterInterface $unitOfWorkAdapter,
    ) {}

    /**
     * @throws AmountWithdrawException
     * @throws UuidException
     * @throws NameException
     * @throws ScheduleException
     * @throws AccountNotFoundException
     * @throws Throwable
     * @throws WithdrawException
     * @throws BalanceException
     * @throws WithDrawPixException
     */
    public function process(CreateWithdrawInputDTO $input): WithdrawResult
    {
        $this->unitOfWorkAdapter->begin();

        try {
            $account = $this->accountRepository->findById($input->accountId)
                ?? throw new AccountNotFoundException();

            $withdraw = WithdrawFactory::create($input, $account);
            $withdrawPix = WithdrawPixFactory::create($input, $withdraw);

            if (!$withdraw->schedule()->scheduled()) {
                $account->subtract($withdraw->amount()->value());
                $this->accountRepository->update($account);
            }

            $this->withdrawRepository->create($withdraw);
            $this->withdrawPixRepository->create($withdrawPix);

            $this->unitOfWorkAdapter->commit();

            return new WithdrawResult($withdraw, $withdrawPix);
        } catch (Throwable $e) {
            $this->unitOfWorkAdapter->rollback();
            throw $e;
        }
    }
}
