<?php

declare(strict_types=1);

namespace App\Domain\Notification;

interface NotificationInterface
{
    public function sendEmail(string $email, array $data, string $template): void;
}
