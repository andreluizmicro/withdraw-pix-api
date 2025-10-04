<?php

declare(strict_types=1);

namespace App\Application\UseCase\WithdrawPix;

use App\Domain\Repository\WithdrawPix\WithdrawPixRepositoryInterface;

class CreateWithdrawPixUseCase
{
    public function __construct(
      private readonly WithdrawPixRepositoryInterface $withdrawPixRepository
    ) {
    }
}
