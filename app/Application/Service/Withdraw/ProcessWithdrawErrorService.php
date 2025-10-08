<?php

declare(strict_types=1);

namespace App\Application\Service\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawErrorInputDTO;
use App\Application\DTO\WithdrawPix\CreateWithdrawPixErrorInputDTO;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;

class ProcessWithdrawErrorService
{
    public function __construct(
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
    ) {}

    public function execute(array $data): void
    {
        $withdrawErrorDto = CreateWithdrawErrorInputDTO::fromArray($data);
        $withdrawPixErrorDto = CreateWithdrawPixErrorInputDTO::fromArray($data);

        $this->withdrawRepository->createError($withdrawErrorDto);
        $this->withdrawPixRepository->createError($withdrawPixErrorDto);
    }
}
