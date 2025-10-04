<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Domain\ValueObject;

use App\Domain\Exception\ScheduleException;
use App\Domain\ValueObject\Schedule;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testShouldCreateScheduleFalse(): void
    {
        $schedule = new Schedule(date: null);

        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertFalse($schedule->scheduled());
    }

    public function testShouldCreateScheduleSuccessfully(): void
    {
        $date = (new DateTimeImmutable())->modify('+2 days');

        $schedule = new Schedule(date: $date);

        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertTrue($schedule->scheduled());
        $this->assertEquals($date, $schedule->date());
    }

    public function testShouldReturnExceptionWhenScheduledForInThePast(): void
    {
        $this->expectException(ScheduleException::class);
        $this->expectExceptionMessage('Scheduled date cannot in the past');

        new Schedule(
            date: (new DateTimeImmutable())->modify('-100 days')
        );
    }

    public function testShouldReturnExceptionWhenScheduledTooFarInFuture(): void
    {
        $this->expectException(ScheduleException::class);
        $this->expectExceptionMessage('Scheduled date cannot be more than 7 days in the future');

        new Schedule(
            date: (new DateTimeImmutable())->modify('+8 days'),
        );
    }
}
