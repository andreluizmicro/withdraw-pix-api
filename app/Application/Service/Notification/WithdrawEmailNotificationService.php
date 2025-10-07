<?php

declare(strict_types=1);

namespace App\Application\Service\Notification;

use App\Domain\Exception\BalanceException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use App\Domain\Notification\EmailNotificationInterface;
use App\Domain\Repository\Account\AccountRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawPixRepositoryInterface;
use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\Enum\EmailTemplate;
use DateTimeImmutable;

class WithdrawEmailNotificationService
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly WithdrawPixRepositoryInterface $withdrawPixRepository,
        private readonly EmailNotificationInterface $notification,
    ) {
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws NameException
     */
    public function notifyError(array $data): void
    {
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
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws NameException
     */
    public function notifySuccess(array $data): void
    {
        $withdrawPix = $this->withdrawPixRepository->findById($data['account_withdraw_pix_id']['value']);
        $withdraw = $this->withdrawRepository->findById($withdrawPix->accountWithdrawId()->value);
        $account = $this->accountRepository->findById($withdraw->accountId()->value);

        $this->notification->sendEmail(
            email: $withdrawPix->key()->value(),
            data: [
                'account_name' => $account->name()->value,
                'amount' => $withdraw->amount()->value(),
                'pix_key' => $withdrawPix->key()->value(),
                'pix_type' => $withdrawPix->type()->value(),
                'date_time' => (new DateTimeImmutable())->format('d/m/Y H:i:s'),
            ],
            template: EmailTemplate::WITHDRAW_PIX_MAIL,
        );
    }
}
