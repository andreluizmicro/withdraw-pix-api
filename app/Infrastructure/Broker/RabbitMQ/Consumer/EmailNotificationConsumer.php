<?php

declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use App\Domain\Notification\EmailNotificationInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\Enum\EmailTemplate;
use DateTimeImmutable;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;
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
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly EmailNotificationInterface $notification,
    ) {
    }

    /**
     * @throws AccountNotFoundException
     * @throws Throwable
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     */
    public function consumeMessage($data, AMQPMessage $message): Result
    {
        if (isset($data['error'])) {
            $account = $this->accountRepository->findById($data['account_id']);

            $this->notification->sendEmail(
                email: $data['pix_key'],
                data: [
                    'account_name' => $account->name()->value,
                    'amount' => $data['amount'],
                    'pix_key' => $data['pix_key'],
                    'pix_type' => $data['pix_type'],
                    'date_time' => (new DateTimeImmutable())->format('d/m/Y H:i:s'),
                ],
                template: EmailTemplate::WITHDRAW_PIX_ERROR_MAIL,
            );

            return Result::ACK;
        }

        $accountWithdrawPixIdReceived = $data['account_withdraw_pix_id']['value'];

        $accountWithdrawPix = $this->withdrawPixRepository->findById($accountWithdrawPixIdReceived);

        if ($accountWithdrawPix === null) {
            throw new AccountNotFoundException();
        }

        $accountWithdraw = $this->withdrawRepository->findById($accountWithdrawPix->accountWithdrawId()->value);

        $account = $this->accountRepository->findById($accountWithdraw->accountId()->value);

        $this->notification->sendEmail(
            email: $accountWithdrawPix->key()->value(),
            data: [
                'account_name' => $account->name()->value,
                'amount' => $accountWithdraw->amount()->value(),
                'pix_key' => $accountWithdrawPix->key()->value(),
                'pix_type' => $accountWithdrawPix->type()->value(),
                'date_time' => (new DateTimeImmutable())->format('d/m/Y H:i:s'),
            ],
            template: EmailTemplate::WITHDRAW_PIX_MAIL,
        );

        return Result::ACK;
    }
}