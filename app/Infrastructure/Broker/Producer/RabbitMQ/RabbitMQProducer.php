<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\Producer\RabbitMQ;

use App\Infrastructure\Factory\RabbitMQFactory;
use Exception;
use Hyperf\Contract\ConfigInterface;
use InvalidArgumentException;

abstract class RabbitMQProducer
{
    protected $connection = null;

    protected $channel = null;

    protected ConfigInterface $config;

    /**
     * @throws Exception
     */
    public function initialize(ConfigInterface $config): void
    {
        if ($this->connection) {
            return;
        }

        $this->config = $config;

        $config = $this->config->get('amqp');

        if (empty($config)) {
            throw new InvalidArgumentException('config not found');
        }

        $this->connection = RabbitMQFactory::createConnection($config);

        $this->channel = $this->connection->channel();
    }

    /**
     * @throws Exception
     */
    abstract public function produce(array $payload, string $destination): void;
}
