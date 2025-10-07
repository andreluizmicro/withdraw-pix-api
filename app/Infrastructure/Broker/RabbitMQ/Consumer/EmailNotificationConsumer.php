<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Application\Service\Notification\WithdrawEmailNotificationService;
use App\Application\Service\Withdraw\ProcessWithdrawErrorService;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Throwable;

#[Consumer(
    exchange: 'withdraw_exchange',
    routingKey: 'withdraw_queue',
    queue: 'withdraw_queue',
    nums: 1
)]
class EmailNotificationConsumer extends ConsumerMessage
{
    public function __construct(
        private readonly WithdrawEmailNotificationService $notificationService,
        private readonly ProcessWithdrawErrorService $processWithdrawErrorService,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     */
    public function consumeMessage($data, AMQPMessage $message): Result
    {
        try {
            if (isset($data['error'])) {
                $this->processWithdrawErrorService->execute($data);

                $this->notificationService->notifyError($data);
            } else {
                $this->notificationService->notifySuccess($data);
            }

            return Result::ACK;
        } catch (Throwable $e) {
            $this->logger->error('error processing message', [
                'error' => $e->getMessage(),
                'payload' => $data,
            ]);

            throw $e;
        }
    }
}
