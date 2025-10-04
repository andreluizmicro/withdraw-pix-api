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
        $schedule = new Schedule(
            scheduled: false,
            scheduledFor: null,
        );

        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertFalse($schedule->scheduled());
        $this->assertNull($schedule->scheduledFor());
    }

    public function testShouldCreateScheduleSuccessfully(): void
    {
        $date = (new DateTimeImmutable())->modify('+2 days');

        $schedule = new Schedule(
            scheduled: true,
            scheduledFor: $date,
        );

        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertTrue($schedule->scheduled());
        $this->assertEquals($date, $schedule->scheduledFor());
    }

    public function testShouldReturnExceptionWhenScheduledForIsRequired(): void
    {
        $this->expectException(ScheduleException::class);
        $this->expectExceptionMessage('Scheduled date is required when scheduling is enabled');

        new Schedule(
            scheduled: true,
            scheduledFor: null,
        );
    }

    public function testShouldReturnExceptionWhenScheduledForInThePast(): void
    {
        $this->expectException(ScheduleException::class);
        $this->expectExceptionMessage('Scheduled date cannot in the past');

        new Schedule(
            scheduled: true,
            scheduledFor: (new DateTimeImmutable())->modify('-100 days'),
        );
    }

    public function testShouldReturnExceptionWhenScheduledTooFarInFuture(): void
    {
        $this->expectException(ScheduleException::class);
        $this->expectExceptionMessage('Scheduled date cannot be more than 7 days in the future');

        new Schedule(
            scheduled: true,
            scheduledFor: (new DateTimeImmutable())->modify('+8 days'),
        );
    }
}
