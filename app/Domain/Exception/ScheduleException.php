<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class ScheduleException extends DomainError
{
    public static function scheduledRequired(): self
    {
        return new self('Scheduled date is required when scheduling is enabled');
    }

    public static function scheduledInPast(): self
    {
        return new self('Scheduled date cannot in the past');
    }

    public static function scheduledTooFarInFuture(): self
    {
        return new self('Scheduled date cannot be more than 7 days in the future');
    }
}
