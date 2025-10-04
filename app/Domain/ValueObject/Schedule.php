<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\ScheduleException;
use DateTimeImmutable;

class Schedule
{
    /**
     * @throws ScheduleException
     */
    public function __construct(
      private ?bool $scheduled = false,
      private ?DateTimeImmutable $scheduledFor = null,
    ) {
        $this->validate();
    }

    /**
     * @throws ScheduleException
     */
    public function validate(): void
    {
        if (! $this->scheduled) {
            return;
        }

        if ($this->scheduledFor === null) {
            throw ScheduleException::scheduledRequired();
        }

        $now = new DateTimeImmutable();
        $maxDate = $now->modify('+7 days');

        if ($this->scheduledFor < $now) {
            throw ScheduleException::scheduledInPast();
        }

        if ($this->scheduledFor > $maxDate) {
            throw ScheduleException::scheduledTooFarInFuture();
        }
    }

    public function scheduled(): bool
    {
        return $this->scheduled;
    }

    public function scheduledFor(): ?DateTimeImmutable {
        return $this->scheduledFor;
    }
}
