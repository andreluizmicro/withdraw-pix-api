<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Entity\AccountWithdraw;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\Handler\Account\AccountWithdrawException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
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
                    'scheduled_for' => $accountWithdraw->schedule()->date()?->format('Y-m-d 23:59:59'),
                ]);
        } catch (Throwable $throwable) {
            throw new AccountWithdrawException('Error creating withdraw', previous: $throwable);
        }
    }

    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     */
    public function findById(string $id): ?AccountWithdraw
    {
        $accountWithdrawDb = $this->database->table('account_withdraw')
            ->where('id', $id)
            ->first();

        if ($accountWithdrawDb === null) {
            return null;
        }

        $date = $accountWithdrawDb->scheduled_for
            ? new DateTimeImmutable( $accountWithdrawDb->scheduled_for)
            : null;

        return new AccountWithdraw(
            id: new Uuid($accountWithdrawDb->id),
            accountId: new Uuid($accountWithdrawDb->account_id),
            method: WithdrawMethod::from($accountWithdrawDb->method),
            amount: new AmountWithdraw((float) $accountWithdrawDb->amount),
            schedule: new Schedule($date),
        );
    }

    /**
     * @return AccountWithdraw[]
     */
    public function findScheduledForToday(?int $limit = 1000): array
    {
         return $this->database->table('account_withdraw')
             ->where('scheduled', true)
             ->whereDate('scheduled_for', date('Y-m-d'))
             ->where('done', false)
             ->orderBy('created_at')
             ->limit($limit)
             ->get('id')
             ->toArray();
    }

    public function updateScheduledForToday(string $accountWithdrawId, bool $done): void
    {
        $this->database->table('account_withdraw')
            ->where('id', $accountWithdrawId)
            ->update([
                'done' => $done,
            ]);
    }
}
