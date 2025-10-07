<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Infrastructure\Enum\EmailTemplate;
use App\Infrastructure\Notification\Mail\WithdrawPixErrorMail;
use App\Infrastructure\Notification\Mail\WithdrawPixMail;
use InvalidArgumentException;

final class MailableFactory
{
    public static function make(EmailTemplate $template, array $data): object
    {
        return match ($template) {
            EmailTemplate::WITHDRAW_PIX_MAIL => new WithdrawPixMail(
                $data['account_name'],
                $data['amount'],
                $data['pix_key'],
                $data['pix_type'],
                $data['date_time'],
                $template
            ),
            EmailTemplate::WITHDRAW_PIX_ERROR_MAIL => new WithdrawPixErrorMail(
                $data['account_name'],
                $data['amount'],
                $data['pix_key'],
                $data['pix_type'],
                $data['date_time'],
                $template
            ),
            default => throw new InvalidArgumentException('Template is not supported ' . $template->value),
        };
    }
}