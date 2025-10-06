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
        Mail::to('test@example.com')->send(
            new WithdrawPixMail('User Test', 100, '123456789', 'CPF', date('Y-m-d H:i:s'), 'withdraw')
        );

//        $mailer = Mail::mailer('smtp');
//
//
//
//       $mailer
//           ->to('fdsfsfd')
//           ->send(
//               new WithdrawPixMail(
//                   $data['account_name'],
//                   $data['amount'],
//                   $data['pix_key'],
//                   $data['pix_type'],
//                   $data['date_time'],
//                   $template
//               )
//           );
    }
}
