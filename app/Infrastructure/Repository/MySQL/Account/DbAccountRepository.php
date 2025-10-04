<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Account;

use App\Domain\Entity\Account;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\Handler\Account\CreateAccountException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UpdateAccountException;
use App\Domain\Exception\UuidException;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use Exception;
use http\Exception\RuntimeException;
use Hyperf\DbConnection\Db;
use Throwable;

class DbAccountRepository implements AccountRepositoryInterface
{
    public function __construct(
        private readonly Db $database,
    ) {
    }

    /**
     * @throws CreateAccountException
     */
    public function create(Account $account): void
    {
        try {
            $this->database->table('account')
                ->insert([
                    'id' => $account->id()->value,
                    'name' => $account->name()->value,
                    'balance' => $account->balance()->value,
                    'is_active' => $account->isActive(),
                ]);
        } catch (Throwable $throwable) {
            throw new CreateAccountException('Error creating account', previous: $throwable);
        }
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws Exception
     */
    public function findById(string $id): ?Account
    {
        $accountDb = $this->database->table('account')
            ->where('id', $id)
            ->first();

        if ($accountDb === null) {
            return null;
        }

        return new Account(
            id: new Uuid($accountDb->id),
            name: new Name($accountDb->name),
            balance: new Balance((float) $accountDb->balance),
            isActive: (bool) $accountDb->is_active,
        );
    }

    /**
     * @throws UpdateAccountException
     */
    public function update(Account $account): void
    {
        try {
            $this->database
                ->table('account')
                ->where('id', $account->id()->value)
                ->update([
                    'balance' => $account->balance()->value,
                ]);
        } catch (Throwable $throwable) {
            throw new UpdateAccountException(
                message: 'Error updating account',
                previous: $throwable
            );
        }
    }
}
