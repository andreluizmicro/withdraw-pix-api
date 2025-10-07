<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Domain\Notification\EmailNotificationInterface;
use App\Infrastructure\Enum\EmailTemplate;
use FriendsOfHyperf\Mail\Facade\Mail;
use Throwable;

class MailHogNotification implements EmailNotificationInterface
{
    private const MAX_ATTEMPTS = 3;
    private const RETRY_DELAY = 3;

    /**
     * @throws Throwable
     */
    public function sendEmail(string $email, array $data, EmailTemplate $template): void
    {
        $mailableClass = $template->getMailableClass();
        $mailable = new $mailableClass(
            $data['account_name'],
            $data['amount'],
            $data['pix_key'],
            $data['pix_type'],
            $data['date_time'],
            $template
        );

        $this->trySend($email, $mailable);
    }

    /**
     * @throws Throwable
     */
    private function trySend(string $email, object $mailable): void
    {
        $attempts = self::MAX_ATTEMPTS;

        while ($attempts > 0) {
            try {
                Mail::mailer('smtp')->to($email)->send($mailable);
                return;
            } catch (Throwable $throwable) {
                var_dump($throwable->getMessage());
                $attempts--;

                if ($attempts === 0) {
                    throw $throwable;
                }

                sleep(self::RETRY_DELAY);
            }
        }
    }
}
