<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Event\Withdraw\AccountWithdrawErrorEvent;
use App\Infrastructure\Broker\Producer\RabbitMQ\AccountWithdrawProducer;
use App\Infrastructure\Enum\Exchanges;
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
            AccountWithdrawErrorEvent::class,
        ];
    }

    /**
     * @throws Exception
     */
    public function process(object $event): void
    {
        if (! $event instanceof AccountWithdrawErrorEvent) {
            return;
        }

        $this->accountWithdrawProducer->produce(
            payload: $event->getProperties(),
            destination: Exchanges::ACCOUNT_WITHDRAW->value,
        );
    }
}
