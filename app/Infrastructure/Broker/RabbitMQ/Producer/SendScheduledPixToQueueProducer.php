<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Producer;

use PhpAmqpLib\Message\AMQPMessage;

class SendScheduledPixToQueueProducer extends Producer
{
    private const QUEUE_NAME = 'scheduled_pix_queue';

    public function produce(array $payload, ?string $exchange = ''): void
    {
        $message = new AMQPMessage(json_encode($payload), [
            'content_type' => 'application/json',
        ]);

        $this->getChannel()->basic_publish($message, $exchange, self::QUEUE_NAME);
    }
}
