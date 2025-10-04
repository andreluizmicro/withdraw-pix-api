<?php

declare(strict_types=1);

namespace App\Domain\Repository\WithdrawPix;

use App\Application\DTO\WithdrawPix\WithdrawPixInputDTO;

interface WithdrawPixRepositoryInterface
{
    public function create(): void;
}
