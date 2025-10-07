<?php

declare(strict_types=1);

namespace App\Infrastructure\Notification\Mail;

use App\Domain\Helper\Money;
use App\Infrastructure\Enum\EmailTemplate;
use FriendsOfHyperf\Mail\Mailable;
use FriendsOfHyperf\Mail\Mailable\Content;
use FriendsOfHyperf\Mail\Mailable\Envelope;

class WithdrawPixErrorMail extends Mailable
{
    public function __construct(
        private readonly string $accountName,
        private readonly float $amount,
        private readonly string $pixKey,
        private readonly string $pixType,
        private readonly string $dateTime,
        private readonly EmailTemplate $template,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Erro de processamento de PIX',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: $this->template->value,
            with: [
                'account_name' => $this->accountName,
                'amount' => Money::formatToBRL($this->amount),
                'pix_key' => $this->pixKey,
                'pix_type' => strtoupper($this->pixType),
                'date_time' => $this->dateTime,
            ],
        );
    }
}