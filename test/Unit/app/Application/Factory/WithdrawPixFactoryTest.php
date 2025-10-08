<?php

declare(strict_types=1);

namespace Tests\Unit\app\Application\Factory;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Application\Factory\WithdrawPixFactory;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Enum\WithdrawMethod;
use App\Domain\Exception\AmountWithdrawException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\AmountWithdraw;
use App\Domain\ValueObject\Schedule;
use App\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class WithdrawPixFactoryTest extends TestCase
{
    /**
     * @throws UuidException
     * @throws WithDrawPixException
     * @throws AmountWithdrawException
     */
    public function testShouldReturnAccountWithdrawPix(): void
    {
        $inputDto = new CreateWithdrawInputDTO(
            accountId: Uuid::random()->value,
            method: 'PIX',
            pixType: 'email',
            pixKey: 'andreluiz@gmail.com',
            amount: 1000,
        );

        $account = new AccountWithdraw(
            id: Uuid::random(),
            accountId: Uuid::random(),
            method: WithdrawMethod::PIX,
            amount: new AmountWithdraw(1000000),
            schedule: new Schedule(),
        );

        $withdrawFactory = WithdrawPixFactory::create($inputDto, $account);

        $this->assertInstanceOf(AccountWithDrawPix::class, $withdrawFactory);
    }
}
