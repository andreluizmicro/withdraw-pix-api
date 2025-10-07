<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawPixErrorEvent;
use App\Infrastructure\Broker\RabbitMQ\Producer\AccountWithdrawProducer;
use Exception;
use Hyperf\Event\Contract\ListenerInterface;

readonly class AccountWithdrawErrorListener implements ListenerInterface
{
    public function __construct(
        private AccountWithdrawProducer $accountWithdrawProducer,

    ) {
    }

    public function listen(): array
    {
        return [
            AccountWithdrawPixErrorEvent::class,
        ];
    }

    /**
     * @throws Exception
     */
    public function process(object $event): void
    {
        if (! $event instanceof AccountWithdrawPixErrorEvent) {
            return;
        }

        $this->accountWithdrawProducer->produce(
            payload: $event->getProperties(),
        );
    }
}
