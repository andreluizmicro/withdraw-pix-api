<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Exception\Handler\Account\CreateAccountException;
use App\Domain\Repository\Withdraw\WithdrawAttemptRepositoryInterface;
use Hyperf\DbConnection\Db;
use Throwable;

class DbWithdrawAttemptRepository implements WithdrawAttemptRepositoryInterface
{
    public function __construct(
        private readonly Db $database,
    ) {
    }

    /**
     * @throws CreateAccountException
     */
    public function createLogFailedAttempt(AccountWithdraw $accountWithdraw): void
    {
        try {
            $this->database->table('account_withdraw_pix')
                ->insert([
                    'id' => $accountWithdraw->id()->value,
                    'account_id' => $accountWithdraw->accountId()->value,
                ]);
        } catch (Throwable $throwable) {
            throw new CreateAccountException('Error creating account', previous: $throwable);
        }
    }
}