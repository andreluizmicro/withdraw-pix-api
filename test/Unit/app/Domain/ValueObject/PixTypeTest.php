<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\ValueObject;

use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\PixType;
use PHPUnit\Framework\TestCase;

class PixTypeTest extends TestCase
{
    public function testShouldCreatePixTypeSuccessFully(): void
    {
        $pixType = new PixType('email');

        $this->assertInstanceOf(PixType::class, $pixType);
        $this->assertEquals('email', $pixType->value());
    }

    public function testShouldReturnWithDrawPixExceptionWithInvalidType(): void
    {
        $this->expectException(WithDrawPixException::class);
        $this->expectExceptionMessage('Invalid pix type');

        new PixType('fake_key');
    }
}
