<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ;

use App\Infrastructure\Factory\RabbitMQFactory;
use Exception;
use Hyperf\Contract\ConfigInterface;
use InvalidArgumentException;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class AbstractRabbitMQ
{
    protected ?AMQPStreamConnection $connection = null;
    protected $channel;

    public function __construct(private ConfigInterface $config)
    {
    }

    /**
     * @throws Exception
     */
    public function getChannel(): AbstractChannel|AMQPChannel
    {
        if ($this->connection) {
            return $this->connection->channel();
        }

        $config = $this->config->get('amqp.default');

        if (empty($config)) {
            throw new InvalidArgumentException('config not found');
        }

        $this->connection = RabbitMQFactory::createConnection($config);

        return $this->connection->channel();
    }
}
