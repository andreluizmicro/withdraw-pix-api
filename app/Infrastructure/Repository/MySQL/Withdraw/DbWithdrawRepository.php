<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Exception\Handler\Account\AccountWithdrawException;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use Hyperf\DbConnection\Db;
use Throwable;

class DbWithdrawRepository implements WithdrawRepositoryInterface
{
    public function __construct(
        private readonly Db $database,
    ) {
    }

    /**
     * @throws AccountWithdrawException
     */
    public function create(AccountWithdraw $accountWithdraw): void
    {
        try {
            $this->database->table('account_withdraw')
                ->insert([
                    'id' => $accountWithdraw->id()->value,
                    'account_id' => $accountWithdraw->accountId()->value,
                    'method' => $accountWithdraw->method()->value,
                    'amount' => $accountWithdraw->amount()->value(),
                    'scheduled' => $accountWithdraw->schedule()->scheduled(),
                    'scheduled_for' => $accountWithdraw->schedule()->date(),
                ]);
        } catch (Throwable $throwable) {
            throw new AccountWithdrawException('Error creating withdraw', previous: $throwable);
        }
    }
}
