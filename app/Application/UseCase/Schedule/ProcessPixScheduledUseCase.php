<?php

declare(strict_types=1);

namespace App\Application\UseCase\Schedule;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\Service\Withdraw\ProcessPixScheduledService;
use App\Domain\Exception\DomainError;
use Throwable;

class ProcessPixScheduledUseCase
{
    public function __construct(
        private readonly ProcessPixScheduledService $withdrawService,
    ) {
    }

    /**
     * @throws DomainError
     */
    public function execute(ProcessScheduleInputDTO $inputDTO): void
    {
        try {
            $this->withdrawService->process($inputDTO);

        } catch (Throwable $e) {
            throw new DomainError($e->getMessage());
        }
    }
}
