<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Enum;

use App\Domain\Enum\PixType;
use PHPUnit\Framework\TestCase;

class PixTypeTest extends TestCase
{
    public function testEnumValuesAreCorrect(): void
    {
        $this->assertSame('email', PixType::EMAIL->value);
    }

    public function testEnumNamesAreCorrect(): void
    {
        $this->assertSame('EMAIL', PixType::EMAIL->name);
    }
}
