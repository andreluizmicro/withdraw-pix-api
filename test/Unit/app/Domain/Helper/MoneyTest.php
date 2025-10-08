<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Helper;

use App\Domain\Helper\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testShouldReturnMoneyFormat(): void
    {
        $amount = 10.59;

        $moneyBRL = Money::formatToBRL($amount);

        $this->assertEquals('R$ ' . number_format($amount, 2, ',', '.'), $moneyBRL);
    }
}
