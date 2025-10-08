<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Factory;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\Factory\WithdrawFactory;
use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\ScheduleException;
use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use Exception;
use PHPUnit\Framework\TestCase;

class WithdrawFactoryTest extends TestCase
{
    /**
     * @throws UuidException
     * @throws AmountWithdrawException
     * @throws ScheduleException
     * @throws BalanceException
     * @throws Exception
     */
    public function testShouldReturnAccountWithdraw(): void
    {
        $inputDto = new CreateWithdrawInputDTO(
            accountId: Uuid::random()->value,
            method: 'PIX',
            pixType: 'email',
            pixKey: 'andreluiz@gmail.com',
            amount: 1000,
        );

        $account = new Account(
            id: Uuid::random(),
            name: new Name('Test Account'),
            balance: new Balance(1000000),
            isActive: true,
        );

        $withdrawFactory = WithdrawFactory::create($inputDto, $account);

        $this->assertInstanceOf(AccountWithdraw::class, $withdrawFactory);
    }
}
