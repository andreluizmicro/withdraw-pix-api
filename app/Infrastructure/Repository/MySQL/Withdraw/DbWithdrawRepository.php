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
use function Swoole\Coroutine\Http\get;

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
                    'scheduled_for' => $accountWithdraw->schedule()->date()?->format('Y-m-d H:i:s'),
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
     * @param int|null $limit
     * @return AccountWithdraw[]
     * @throws AmountWithdrawException
     * @throws ScheduleException
     * @throws UuidException
     * @throws \DateMalformedStringException
     */
    public function findScheduledPix(?int $limit = 1000): array
    {
         $withdrawList = $this->database->table('account_withdraw')
            ->where('scheduled', true)
            ->orderBy('created_at')
            ->limit($limit)
            ->get()->toArray();

        return array_map(/**
         * @throws UuidException
         * @throws ScheduleException
         * @throws AmountWithdrawException
         */ fn($withdraw) => new AccountWithdraw(
            id: new Uuid($withdraw->id),
            accountId: new Uuid($withdraw->account_id),
            method: WithdrawMethod::from($withdraw->method),
            amount: new AmountWithdraw((float) $withdraw->amount),
            schedule: new Schedule(
                date: $withdraw->scheduled_for ? new DateTimeImmutable($withdraw->scheduled_for) : null,
            ),
        ), $withdrawList);
    }
}
