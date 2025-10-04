<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Core\Domain\Entity;

use App\Domain\Entity\WithDrawPix;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class WithdrawPixTest extends TestCase
{
    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    public function testShouldCreateWithdrawPix(): void
    {
        $id = Uuid::random();
        $accountWithdrawId = UUID::random();
        $pixType = new PixType('email');

        $withDrawPix = new WithDrawPix(
            id: $id,
            accountWithdrawId: $accountWithdrawId,
            type: $pixType,
            key: new PixKey(
                type: $pixType,
                key: 'andreluizsilva@gmail.com'
            ),
        );

        $this->assertInstanceOf(WithDrawPix::class, $withDrawPix);
        $this->assertEquals($id->value, $withDrawPix->id()->value);
        $this->assertEquals($accountWithdrawId->value, $withDrawPix->accountWithdrawId()->value);
        $this->assertEquals($withDrawPix->type()->value(), $withDrawPix->type()->value());
        $this->assertEquals($withDrawPix->key()->value(), $withDrawPix->key()->value());
    }
}
