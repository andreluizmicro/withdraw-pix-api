<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Domain\Event\WithdrawPix\WithdrawPixProcessedEvent;
use App\Domain\Notification\EmailNotificationInterface;
use App\Infrastructure\Broker\RabbitMQ\Producer\AccountWithdrawProducer;
use Hyperf\Event\Contract\ListenerInterface;

class NotificationListener implements ListenerInterface
{
    public function __construct(
        private EmailNotificationInterface $notificationService,

    ) {
    }

    public function listen(): array
    {
        return [
            WithdrawPixProcessedEvent::class,
        ];
    }

    /**
     * @throws Exception
     */
    public function process(object $event): void
    {
        if (! $event instanceof AccountWithdrawPixCreatedEvent) {
            return;
        }

        $this->accountWithdrawProducer->produce(
            payload: $event->getProperties(),
        );
    }
}