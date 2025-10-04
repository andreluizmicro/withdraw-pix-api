<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\Producer\RabbitMQ;

use App\Domain\Broker\Producer\RabbitMQ\ProducerInterface;

class AccountWithdrawProducer implements ProducerInterface
{

    public function produce(array $payload, string $exchange): void
    {

    }
}