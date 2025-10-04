<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawCreatedEvent;
use Hyperf\Event\Contract\ListenerInterface;

class AccountWithdrawCreatedListener implements ListenerInterface
{
    public function __construct(

    ) {
    }

    public function listen(): array
    {
        return [
            AccountWithdrawCreatedEvent::class,
        ];
    }

    public function process(object $event): void
    {
        if (! $event instanceof AccountWithdrawCreatedEvent) {
            return;
        }


    }
}