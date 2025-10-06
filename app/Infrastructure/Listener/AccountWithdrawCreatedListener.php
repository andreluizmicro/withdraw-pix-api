<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawPixCreatedEvent;
use App\Infrastructure\Broker\Producer\RabbitMQ\AccountWithdrawProducer;
use App\Infrastructure\Enum\Exchange;
use Exception;
use Hyperf\Event\Contract\ListenerInterface;

readonly class AccountWithdrawCreatedListener implements ListenerInterface
{
    public function __construct(
        private AccountWithdrawProducer $accountWithdrawProducer,

    ) {
    }

    public function listen(): array
    {
        return [
            AccountWithdrawPixCreatedEvent::class,
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
            destination: Exchange::ACCOUNT_WITHDRAW->value,
        );
    }
}
