<?php

declare(strict_types=1);

namespace App\Infrastructure\Enum;


use App\Infrastructure\Notification\Mail\WithdrawPixErrorMail;
use App\Infrastructure\Notification\Mail\WithdrawPixMail;

enum EmailTemplate: string
{
    case WITHDRAW_PIX_MAIL = 'mail.html.withdraw_pix_mail';
    case WITHDRAW_PIX_ERROR_MAIL = 'mail.html.withdraw_pix_error_mail';

    public function getMailableClass(): string
    {
        return match ($this) {
            self::WITHDRAW_PIX_MAIL => WithdrawPixMail::class,
            self::WITHDRAW_PIX_ERROR_MAIL => WithdrawPixErrorMail::class,
        };
    }
}
