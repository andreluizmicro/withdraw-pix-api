<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Core\Domain\ValueObject;

use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use PHPUnit\Framework\TestCase;

class PixKeyTest extends TestCase
{
    /**
     * @throws WithDrawPixException
     */
    public function testShouldCreatePixKeySuccessfully(): void
    {
        $pixKey = new PixKey(
            type: new PixType('email'),
            key: 'andresilva@gmail.com',
        );

        $this->assertInstanceOf(PixKey::class, $pixKey);
        $this->assertEquals('email', $pixKey->type()->value());
        $this->assertEquals('andresilva@gmail.com', $pixKey->value());
    }

    public function testShouldReturnWithDrawPixExceptionWithInvalidType(): void
    {
        $this->expectException(WithDrawPixException::class);
        $this->expectExceptionMessage('Invalid pix type');

        new PixKey(
            type: new PixType('fake'),
            key: 'andresilva@gmail.com',
        );
    }

    public function testShouldReturnWithDrawPixExceptionWithInvalidKey(): void
    {
        $this->expectException(WithDrawPixException::class);
        $this->expectExceptionMessage('Invalid pix key');

        new PixKey(
            type: new PixType('email'),
            key: 'andre',
        );
    }
}
