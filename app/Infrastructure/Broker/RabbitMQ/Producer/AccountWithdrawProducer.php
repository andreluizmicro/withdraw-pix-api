<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Producer;

use Exception;
use Hyperf\Contract\ConfigInterface;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class AccountWithdrawProducer extends Producer
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
    public function produce(array $payload, string $exchange): void
    {
        $message = new AMQPMessage(json_encode($payload), [
            'content_type' => 'application/json',
        ]);

        $this->channel->basic_publish($message,'', 'withdraw_queue');
    }
}
