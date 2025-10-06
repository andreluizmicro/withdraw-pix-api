<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\Producer\RabbitMQ;

use App\Infrastructure\Factory\RabbitMQFactory;
use Exception;
use Hyperf\Contract\ConfigInterface;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class RabbitMQProducer
{
    protected $connection = null;

    protected $channel = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __construct(
        protected readonly ContainerInterface $container,
    )
    {
        if ($this->connection) {
            return;
        }

        $config = $this->container->get(ConfigInterface::class)->get('amqp');

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
