<?php

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;

#[Consumer(
    exchange: 'withdraw_exchange',
    routingKey: 'withdraw_queue',
    queue: 'withdraw_queue',
    nums: 1
)]
class WithdrawQueueConsumer extends ConsumerMessage
{
    public function consumeMessage($data, AMQPMessage $message): Result
    {
        print_r($data);
        return Result::ACK;
    }
}