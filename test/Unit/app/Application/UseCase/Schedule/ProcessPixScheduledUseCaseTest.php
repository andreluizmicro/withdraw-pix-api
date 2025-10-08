<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\UseCase\Schedule;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\Service\Withdraw\ProcessPixScheduledService;
use App\Application\UseCase\Schedule\ProcessPixScheduledUseCase;
use App\Domain\Exception\DomainError;
use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProcessPixScheduledUseCaseTest extends TestCase
{
    private ProcessPixScheduledService $processPixScheduleService;

    private ProcessPixScheduledUseCase $processPixScheduledUseCase;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->processPixScheduleService = $this->createMock(ProcessPixScheduledService::class);

        $this->processPixScheduledUseCase = new ProcessPixScheduledUseCase($this->processPixScheduleService);
    }

    /**
     * @throws DomainError
     * @throws UuidException
     */
    public function testShouldProcessPixScheduledUseCaseExecute(): void
    {
        $this->processPixScheduleService->expects($this->once())
            ->method('process');

        $this->processPixScheduledUseCase->execute(
            inputDTO: new ProcessScheduleInputDTO(accountWithdrawId: Uuid::random()->value),
        );
    }

    /**
     * @throws DomainError
     */
    public function testShouldThrowExceptionIfUuidIsInvalid(): void
    {
        $this->expectException(DomainError::class);

        $this->processPixScheduleService->expects($this->once())
            ->method('process')
            ->willThrowException(new UuidException());

        $this->processPixScheduledUseCase->execute(
            inputDTO: new ProcessScheduleInputDTO(accountWithdrawId: 'invalid-uuid'),
        );
    }
}
