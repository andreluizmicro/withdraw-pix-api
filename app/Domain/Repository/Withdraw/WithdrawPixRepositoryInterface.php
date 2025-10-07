<?php

declare(strict_types=1);

namespace App\Domain\Repository\Withdraw;

use App\Application\DTO\WithdrawPix\CreateWithdrawPixErrorInputDTO;
use App\Domain\Entity\AccountWithDrawPix;

interface WithdrawPixRepositoryInterface
{
    public function create(AccountWithDrawPix $accountWithdrawPix): void;

    public function createError(CreateWithdrawPixErrorInputDTO $createWithdrawErrorInputDTO): void;

    public function findById(string $id): ?AccountWithDrawPix;

    public function findByAccountId(string $accountWithDrawId): ?AccountWithDrawPix;
}
