<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum EventCategory: string
{
    case WITHDRAWN_CREATION = 'WITHDRAWN';
    case WITHDRAW_ERROR = 'WITHDRAW_ERROR';
}

