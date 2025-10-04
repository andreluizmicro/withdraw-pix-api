<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Domain\ValueObject;

use App\Domain\Exception\AmountWithdrawException;
use App\Domain\ValueObject\AmountWithdraw;
use PHPUnit\Framework\TestCase;

class AmountWithdrawTest extends TestCase
{
    /**
     * @throws AmountWithdrawException
     */
    public function testShouldCreateAmountWithdraw(): void
    {
        $amountWithdraw = new AmountWithdraw(value: 0.50);

        $this->assertEquals(0.50, $amountWithdraw->value());
    }

    /**
     * @throws AmountWithdrawException
     */
    public function testShouldReturnExpectedAmountWithdrawWithInvalidValue(): void
    {
        $this->expectExceptionMessage(AmountWithdrawException::class);
        $this->expectExceptionMessage('Invalid amount');

        $amountWithdraw = new AmountWithdraw(value: 0.00);
    }
}
