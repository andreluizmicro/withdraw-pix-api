<?php

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\Handler\Account\AccountNotFoundException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use App\Domain\Helper\Money;
use App\Domain\Notification\NotificationInterface;
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
class WithdrawQueueConsumer extends ConsumerMessage
{
    private int $remainingAttempts = 3;

    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly NotificationInterface $notification,
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
                'amount' => Money::formatToBRL($accountWithdraw->amount()->value()),
                'pix_key' => $accountWithdrawPix->key()->value(),
                'pix_type' => $accountWithdrawPix->type()->value(),
                'date' => (new DateTimeImmutable())->format('d/m/Y H:i:s'),
            ],
            template: EmailTemplate::WITHDRAW_PIX_MAIL->value,
        );

        $this->sendNotification($accountWithdraw, $accountWithdrawPix, $account);

        return Result::ACK;
    }

    /**
     * @throws Throwable
     */
    private function sendNotification(
        AccountWithdraw $accountWithdraw,
        AccountWithdrawPix $accountWithdrawPix,
        ?Account $account = null,
    ): void
    {
        while ($this->remainingAttempts > 0) {
            try {
                $this->notification->sendEmail(
                    email: $accountWithdrawPix->key()->value(),
                    data: [
                        'account_name' => $account->name()->value,
                        'amount' => Money::formatToBRL($accountWithdraw->amount()->value()),
                        'pix_key' => $accountWithdrawPix->key()->value(),
                        'pix_type' => $accountWithdrawPix->type()->value(),
                        'date' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                    ],
                    template: EmailTemplate::WITHDRAW_PIX_MAIL->value,
                );

                $this->remainingAttempts = 0;


            } catch (Throwable $throwable) {
                $this->remainingAttempts--;

                if ($this->remainingAttempts === 0) {
                    throw $throwable;
                }
            }
        }
    }
}