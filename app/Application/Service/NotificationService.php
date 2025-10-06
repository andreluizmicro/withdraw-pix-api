<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Notification\NotificationInterface;
use App\Mail\WithdrawPixMail;
use FriendsOfHyperf\Mail\Facade\Mail;

class NotificationService implements NotificationInterface
{
    public function __construct()
    {
    }

    public function sendEmail(string $email, array $data, string $template): void
    {
        $mailer = Mail::mailer('smtp');

       $mailer
           ->to($email)
           ->send(
               new WithdrawPixMail(
                   $data['account_name'],
                   $data['amount'],
                   $data['pix_key'],
                   $data['pix_type'],
                   $data['date_time'],
                   $template
               )
           );
    }
}
