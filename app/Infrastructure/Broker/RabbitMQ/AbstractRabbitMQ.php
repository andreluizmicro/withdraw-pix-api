<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ;

use App\Infrastructure\Factory\RabbitMQFactory;
use Exception;
use Hyperf\Contract\ConfigInterface;
use InvalidArgumentException;

abstract class AbstractRabbitMQ
{
    protected $connection;

    protected $channel;

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

        $config = $this->config->get('amqp.default');

        if (empty($config)) {
            throw new InvalidArgumentException('config not found');
        }

        $this->connection = RabbitMQFactory::createConnection($config);

        $this->channel = $this->connection->channel();
    }
}
