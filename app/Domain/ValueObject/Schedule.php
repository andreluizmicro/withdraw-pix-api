<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\ScheduleException;
use DateTimeImmutable;

class Schedule
{
    private bool $scheduled = false;

    /**
     * @throws ScheduleException
     */
    public function __construct(
      private ?DateTimeImmutable $date = null,
    ) {
        $this->validate();
    }

    /**
     * @throws ScheduleException
     */
    public function validate(): void
    {
        if ($this->date === null) {
            return;
        }

        $now = new DateTimeImmutable();
        $maxDate = $now->modify('+7 days');

        if ($this->date < $now) {
            throw ScheduleException::scheduledInPast();
        }

        if ($this->date > $maxDate) {
            throw ScheduleException::scheduledTooFarInFuture();
        }

        $this->scheduled = true;
    }

    public function date(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function scheduled(): bool
    {
        return $this->scheduled;
    }
}
