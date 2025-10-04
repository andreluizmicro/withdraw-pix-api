<?php

declare(strict_types=1);

namespace App\Domain\Broker\Producer\RabbitMQ;

interface ProducerInterface
{
    public function produce(array $payload, string $exchange): void;
}
