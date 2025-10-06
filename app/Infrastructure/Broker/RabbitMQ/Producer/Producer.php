<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Producer;

use App\Infrastructure\Broker\RabbitMQ\AbstractRabbitMQ;
use Exception;

abstract class Producer extends AbstractRabbitMQ
{
    /**
     * @throws Exception
     */
    abstract public function produce(array $payload, string $exchange): void;
}