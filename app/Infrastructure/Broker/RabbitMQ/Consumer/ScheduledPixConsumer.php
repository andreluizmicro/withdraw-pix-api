<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Application\DTO\Schedule\ProcessScheduleInputDTO;
use App\Application\UseCase\Schedule\ProcessSchedulePixUseCase;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;

#[Consumer(
    exchange: 'withdraw_exchange',
    routingKey: 'scheduled_pix_queue',
    queue: 'scheduled_pix_queue',
    nums: 1
)]
class ScheduledPixConsumer extends ConsumerMessage
{
    public function __construct(
        private ProcessSchedulePixUseCase $processSchedulePixUseCase,
    ) {
    }

    /**
     * @throws AccountNotFoundException
     */
    public function consumeMessage($data, AMQPMessage $message): Result
    {
        $processScheduleInputDto = new ProcessScheduleInputDTO($data['account_withdraw_id']['id']);

        $this->processSchedulePixUseCase->execute($processScheduleInputDto);

        return Result::ACK;
    }
}
