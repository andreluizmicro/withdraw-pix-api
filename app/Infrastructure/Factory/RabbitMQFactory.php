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
        return new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost'],
        );
    }
}
