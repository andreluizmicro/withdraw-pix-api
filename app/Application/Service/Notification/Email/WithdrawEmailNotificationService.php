<?php

declare(strict_types=1);

namespace App\Application\Service\Notification\Email;

use App\Domain\Notification\EmailNotificationInterface;
use App\Infrastructure\Enum\EmailTemplate;
use App\Infrastructure\Notification\Mail\WithdrawPixMail;
use FriendsOfHyperf\Mail\Facade\Mail;
use Throwable;

class WithdrawEmailNotificationService implements EmailNotificationInterface
{
    /**
     * @throws Throwable
     */
    public function sendEmail(string $email, array $data, EmailTemplate $template): void
    {
        $mailer = Mail::mailer('smtp');

        $remainingAttempts = 3;

        while ($remainingAttempts > 0) {
            try {
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

                $remainingAttempts = 0;
            } catch (Throwable $throwable) {
                $remainingAttempts--;

                if ($remainingAttempts === 0) {
                    throw $throwable;
                }

                sleep(3);
            }
        }
    }
}
