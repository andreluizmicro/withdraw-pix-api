<?php

declare(strict_types=1);

namespace App\Infrastructure\Enum;

enum EmailTemplate: string
{
    case WITHDRAW_PIX_MAIL = 'infrastructure.notification.email.template.withdraw_pix';
}
