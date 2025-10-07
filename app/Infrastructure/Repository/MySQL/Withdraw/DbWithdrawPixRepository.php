<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Exception\Handler\Account\AccountWithdrawException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Uuid;
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

    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    public function findById(string $id): ?AccountWithDrawPix
    {
        $accountWithdrawPixDb = $this->database->table('account_withdraw_pix')
            ->where('id', $id)
            ->first();

        if ($accountWithdrawPixDb === null) {
            return null;
        }

        $type = new PixType($accountWithdrawPixDb->type);

        return new AccountWithDrawPix(
            id: new Uuid($accountWithdrawPixDb->id),
            accountWithdrawId: new Uuid($accountWithdrawPixDb->account_withdraw_id),
            type: $type,
            key: new PixKey(
                type: $type,
                key: $accountWithdrawPixDb->key,
            )
        );
    }

    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    public function findByAccountId(string $accountWithDrawId): ?AccountWithDrawPix
    {
        $accountWithdrawPixDb = $this->database->table('account_withdraw_pix')
            ->where('account_withdraw_id', $accountWithDrawId)
            ->first();

        if ($accountWithdrawPixDb === null) {
            return null;
        }

        $type = new PixType($accountWithdrawPixDb->type);

        return new AccountWithDrawPix(
            id: new Uuid($accountWithdrawPixDb->id),
            accountWithdrawId: new Uuid($accountWithdrawPixDb->account_withdraw_id),
            type: $type,
            key: new PixKey(
                type: $type,
                key: $accountWithdrawPixDb->key,
            )
        );
    }
}
