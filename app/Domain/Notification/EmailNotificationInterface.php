<?php

declare(strict_types=1);

namespace App\Domain\Notification;

use App\Infrastructure\Enum\EmailTemplate;

interface EmailNotificationInterface
{
    public function sendEmail(string $email, array $data, EmailTemplate $template): void;
}
