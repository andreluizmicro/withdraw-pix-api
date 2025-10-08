<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Enum;

use App\Domain\Enum\WithdrawMethod;
use PHPUnit\Framework\TestCase;

class WithdrawMethodTest extends TestCase
{
    public function testEnumValuesAreCorrect(): void
    {
        $this->assertSame('PIX', WithdrawMethod::PIX->value);
    }

    public function testEnumNamesAreCorrect(): void
    {
        $this->assertSame('PIX', WithdrawMethod::PIX->name);
    }
}
