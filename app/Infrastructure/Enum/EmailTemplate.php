<?php

declare(strict_types=1);

namespace App\Infrastructure\Enum;

enum EmailTemplate: string
{
    case WITHDRAW_PIX_MAIL = 'mail.html.withdraw';
    case WITHDRAW_PIX_ERROR_MAIL = 'mail.html.withdraw_error';
}
