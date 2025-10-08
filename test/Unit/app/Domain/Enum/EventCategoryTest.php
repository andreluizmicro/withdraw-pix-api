<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Enum;

use App\Domain\Enum\EventCategory;
use PHPUnit\Framework\TestCase;

class EventCategoryTest extends TestCase
{
    public function testEnumValuesAreCorrect(): void
    {
        $this->assertSame('WITHDRAWN', EventCategory::WITHDRAWN_CREATION->value);
        $this->assertSame('WITHDRAW_ERROR', EventCategory::WITHDRAW_ERROR->value);
        $this->assertSame('WITHDRAW_PIX_PROCESSED', EventCategory::WITHDRAW_PIX_PROCESSED->value);
    }

    public function testEnumNamesAreCorrect(): void
    {
        $this->assertSame('WITHDRAWN_CREATION', EventCategory::WITHDRAWN_CREATION->name);
        $this->assertSame('WITHDRAW_ERROR', EventCategory::WITHDRAW_ERROR->name);
        $this->assertSame('WITHDRAW_PIX_PROCESSED', EventCategory::WITHDRAW_PIX_PROCESSED->name);
    }
}
