<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Exception\Handler\Account\AccountWithdrawException;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use Hyperf\DbConnection\Db;
use Throwable;

class DbWithdrawPixRepository implements WithdrawPixRepositoryInterface
{
    public function __construct(
        private readonly Db $database,
    ) {
    }

    /**
     * @throws AccountWithdrawException
     */
    public function create(AccountWithDrawPix $accountWithdrawPix): void
    {
        try {
            $this->database->table('account_withdraw_pix')
                ->insert([
                    'id' => $accountWithdrawPix->id()->value,
                    'account_withdraw_id' => $accountWithdrawPix->accountWithdrawId()->value,
                    'type' => $accountWithdrawPix->type()->value(),
                    'key' => $accountWithdrawPix->key()->value(),
                ]);
        } catch (Throwable $throwable) {
            throw new AccountWithdrawException('Error creating withdraw', previous: $throwable);
        }
    }
}