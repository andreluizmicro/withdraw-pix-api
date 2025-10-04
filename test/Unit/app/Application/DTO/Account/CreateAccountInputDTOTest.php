<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Application\DTO\Account;

use App\Application\DTO\Account\CreateAccountInputDTO;
use PHPUnit\Framework\TestCase;

class CreateAccountInputDTOTest extends TestCase
{
    public function testShouldCreateAccountInputDTO(): void
    {
        $dto = new CreateAccountInputDTO('andre silva');
        $this->assertSame('andre silva', $dto->name);
    }
}