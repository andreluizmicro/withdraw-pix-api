<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\Producer\RabbitMQ;

use Exception;
use Hyperf\Contract\ConfigInterface;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class AccountWithdrawProducer extends RabbitMQProducer
{
    /**
     * @throws Exception
     */
    public function __construct(ConfigInterface $config)
    {
        $this->initialize($config);
    }

    /**
     * @throws Exception
     */
    public function produce(array $payload, string $destination): void
    {
        $this->channel->exchange_declare(
            exchange: $destination,
            type: AMQPExchangeType::FANOUT,
            durable: true,
            auto_delete: false,
        );

        $message = new AMQPMessage(json_encode($payload), [
            'content_type' => 'application/json',
        ]);

        $this->channel->basic_publish($message, $destination);
    }
}

