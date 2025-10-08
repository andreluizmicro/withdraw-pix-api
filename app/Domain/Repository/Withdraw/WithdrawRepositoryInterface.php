<?php

declare(strict_types=1);

namespace App\Domain\Repository\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Domain\Entity\AccountWithdraw;
use App\Infrastructure\DTO\Withdraw\UpdateWithdrawProcessErrorDTO;

interface WithdrawRepositoryInterface
{
    public function create(AccountWithdraw $accountWithdraw): void;

    public function createError(CreateWithdrawErrorInputDTO $errorDTO): void;

    public function findById(string $id): ?AccountWithdraw;

    /**
     * @return AccountWithdraw[]
     */
    public function findScheduledForToday(?int $limit = 1000): array;

    public function updateScheduledForToday(UpdateWithdrawProcessErrorDTO $errorDTO): void;
}
