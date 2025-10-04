<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Domain\Validation;

use App\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationTest extends TestCase
{
    public function testDomainValidationEmail(): void
    {
        $this->assertTrue(DomainValidation::email('andreluizmicro@gmail.com'));
    }

    public function testShouldReturnFalseIfEmailIsInvalid(): void
    {
        $this->assertFalse(DomainValidation::email('andre'));
        $this->assertFalse(DomainValidation::email('andre@@@'));
        $this->assertFalse(DomainValidation::email('11111111111@111111'));
        $this->assertFalse(DomainValidation::email('111111111111111111'));
    }
}
