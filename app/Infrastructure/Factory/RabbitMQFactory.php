<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQFactory
{
    /**
     * @throws Exception
     */
    public static function createConnection(array $configs): AMQPStreamConnection
    {
        $amqp = $configs['amqp'];

        return new AMQPStreamConnection(
            host: $amqp['host'],
            port: $amqp['port'],
            user: $amqp['user'],
            password: $amqp['password'],
            vhost: $amqp['vhost'],
        );
    }
}
