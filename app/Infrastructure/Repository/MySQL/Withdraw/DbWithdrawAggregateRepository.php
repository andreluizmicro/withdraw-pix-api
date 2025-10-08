<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\MySQL\Withdraw;

use App\Domain\Repository\Withdraw\WithdrawAggregateRepositoryInterface;
use App\Infrastructure\DTO\Withdraw\WithdrawAggregateDTO;
use Hyperf\DbConnection\Db;

class DbWithdrawAggregateRepository implements WithdrawAggregateRepositoryInterface
{
    public function __construct(
        private readonly Db $database
    ) {
    }

    public function findWithdrawAggregate(string $accountWithdrawId): ?WithdrawAggregateDTO
    {
        $pixData = $this->database
            ->table('account_withdraw_pix')
            ->where('account_withdraw_id', $accountWithdrawId)
            ->first();

        if (!$pixData) {
            return null;
        }

        $withdrawData = $this->database
            ->table('account_withdraw')
            ->where('id', $pixData->account_withdraw_id)
            ->first();

        if (!$withdrawData) {
            return null;
        }

        $accountData = $this->database
            ->table('account')
            ->where('id', $withdrawData->account_id)
            ->first();

        if (!$accountData) {
            return null;
        }

        return new WithdrawAggregateDTO(
            accountId: $accountData->id,
            accountName: $accountData->name,
            accountBalance: (float) $accountData->balance,
            withdrawId: $withdrawData->id,
            withdrawAmount: (float) $withdrawData->amount,
            withdrawScheduled: (bool) $withdrawData->scheduled,
            withdrawScheduledFor: $withdrawData->scheduled_for,
            pixId: $pixData->id,
            pixKey: $pixData->key,
            pixType: $pixData->type,
        );
    }
}
