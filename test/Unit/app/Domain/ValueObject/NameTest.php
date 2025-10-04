<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Domain\ValueObject;

use App\Domain\Exception\NameException;
use App\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /**
     * @throws NameException
     */
    public function testShouldCreateValidName(): void
    {
        $name = 'John Doe';
        $nameValueObject = new Name($name);

        $this->assertEquals(strtoupper($name), $nameValueObject->value);
    }

    public function testShouldThrowExceptionForNullName(): void
    {
        $this->expectException(NameException::class);
        $this->expectExceptionMessage('Name cannot be null');

        new Name('');
    }

    public function testShouldThrowExceptionForShortName(): void
    {
        $this->expectException(NameException::class);
        $this->expectExceptionMessage('Name must be between 3 and 255 characters');

        new Name('Jo');
    }
}
