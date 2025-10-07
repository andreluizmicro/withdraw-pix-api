<?php

namespace App\Infrastructure\Notification\Mail;

use App\Domain\Helper\Money;
use App\Infrastructure\Enum\EmailTemplate;
use FriendsOfHyperf\Mail\Mailable;
use FriendsOfHyperf\Mail\Mailable\Content;
use FriendsOfHyperf\Mail\Mailable\Envelope;

class WithdrawPixMail extends Mailable
{
    public function __construct(
        private readonly string $accountName,
        private readonly float  $amount,
        private readonly string $pixKey,
        private readonly string $type,
        private readonly string $dateTime,
        private readonly EmailTemplate $template,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'PIX Withdrawal Confirmation');
    }

    public function content(): Content
    {
        return new Content(
            view:  $this->template->value,
            with: [
                'account_name' => $this->accountName,
                'amount' => Money::formatToBRL($this->amount),
                'pixKey' => $this->pixKey,
                'type' => $this->type,
                'date_time' => $this->dateTime,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
