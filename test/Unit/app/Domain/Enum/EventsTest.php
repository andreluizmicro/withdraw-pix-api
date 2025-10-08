<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Enum;

use App\Domain\Enum\Events;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    public function testEnumValuesAreCorrect(): void
    {
        $this->assertSame('account_withdraw_created', Events::ACCOUNT_WITHDRAW_CREATED->value);
        $this->assertSame('account_withdraw_error', Events::ACCOUNT_WITHDRAW_ERROR->value);
        $this->assertSame('account_withdraw_pix_processed', Events::ACCOUNT_WITHDRAW_PIX_PROCESSED->value);
    }

    public function testEnumNamesAreCorrect(): void
    {
        $this->assertSame('ACCOUNT_WITHDRAW_CREATED', Events::ACCOUNT_WITHDRAW_CREATED->name);
        $this->assertSame('ACCOUNT_WITHDRAW_ERROR', Events::ACCOUNT_WITHDRAW_ERROR->name);
        $this->assertSame('ACCOUNT_WITHDRAW_PIX_PROCESSED', Events::ACCOUNT_WITHDRAW_PIX_PROCESSED->name);
    }
}
