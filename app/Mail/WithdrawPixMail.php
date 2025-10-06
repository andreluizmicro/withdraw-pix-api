<?php

namespace App\Mail;

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
        private readonly string $template,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'PIX Withdrawal Confirmation');
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->template,
            with: [
                'account_name' => $this->accountName,
                'amount' => $this->amount,
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
