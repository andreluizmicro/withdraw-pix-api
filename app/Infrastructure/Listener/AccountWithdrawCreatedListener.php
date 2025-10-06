<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawCreatedEvent;
use App\Infrastructure\Broker\Producer\RabbitMQ\AccountWithdrawProducer;
use App\Infrastructure\Enum\Exchanges;
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
            AccountWithdrawCreatedEvent::class,
        ];
    }

    /**
     * @throws Exception
     */
    public function process(object $event): void
    {
        if (! $event instanceof AccountWithdrawCreatedEvent) {
            return;
        }

        $this->accountWithdrawProducer->produce(
            payload: $event->getProperties(),
            destination: Exchanges::ACCOUNT_WITHDRAW->value,
        );
    }
}
