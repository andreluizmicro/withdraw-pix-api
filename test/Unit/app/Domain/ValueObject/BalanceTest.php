<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Domain\ValueObject;

use App\Domain\Exception\BalanceException;
use App\Domain\ValueObject\Balance;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    /**
     * @throws BalanceException
     */
    public function testShouldCreateBalance(): void
    {
        $balance = new Balance(100.50);
        $this->assertEquals(100.50, $balance->value);
    }

    public function testShouldThrowExceptionForNegativeBalance(): void
    {
        $this->expectException(BalanceException::class);
        $this->expectExceptionMessage('Balance cannot be negative');

        new Balance(-50.00);
    }
}
