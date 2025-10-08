<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\ValueObject;

use App\Domain\Exception\UuidException;
use App\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidTest extends TestCase
{
    /**
     * @throws UuidException
     */
    public function testShouldCreateValidUuid(): void
    {
        $uuid = Uuid::random();
        $this->assertTrue(RamseyUuid::isValid($uuid->toString()));
    }

    public function testShouldThrowExceptionForInvalidUuid(): void
    {
        $this->expectException(UuidException::class);
        $this->expectExceptionMessage('Invalid UUID provided.');
        new Uuid('invalid-uuid-string');
    }
}
