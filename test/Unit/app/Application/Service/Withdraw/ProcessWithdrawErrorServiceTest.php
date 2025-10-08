<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Service\Withdraw;

use App\Application\Service\Withdraw\ProcessWithdrawErrorService;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProcessWithdrawErrorServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testShouldProcessWithdrawError(): void
    {
        $withdrawRepository = $this->createMock(WithdrawRepositoryInterface::class);
        $withdrawPixRepository = $this->createMock(WithdrawPixRepositoryInterface::class);

        $service = new ProcessWithdrawErrorService(
            $withdrawRepository,
            $withdrawPixRepository,
        );

        $withdrawRepository->expects($this->once())
            ->method('createError');

        $withdrawPixRepository->expects($this->once())
            ->method('createError');

        $service->execute([
            'id' => 'c985ee25-5605-4c6b-a8d2-478edf894136',
            'account_id' => 'db9fcd34-f1b2-4c35-a110-1adb472ccb71',
            'method' => 'pix',
            'pix_type' => 'email',
            'pix_key' => 'andreluiz@gmail.com',
            'amount' => 5000000,
            'error_reason' => 'Insufficient balance for withdrawal',
            'scheduled' => false,
            'scheduled_for' => null,
            'done' => true,
            'error' => true,
        ]);
    }
}
