<?php

declare(strict_types=1);

namespace App\Application\DTO\Withdraw;

class CreateWithdrawErrorInputDTO
{
    public function __construct(
        public string $id,
        public string $accountId,
        public string $method,
        public string $pixType,
        public string $pixKey,
        public float $amount,
        public string $errorReason,
        public ?bool $scheduled = false,
        public ?string $scheduledFor = null,
        public ?bool $done = false,
        public ?bool $error = true,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            accountId: $data['account_id'],
            method: $data['method'],
            pixType: $data['pix_type'],
            pixKey: $data['pix_key'],
            amount: $data['amount'],
            errorReason: $data['error_reason'],
            scheduled: $data['scheduled'],
            scheduledFor: $data['scheduled_for'],
            done: $data['done'],
            error: $data['error'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'method' => $this->method,
            'pix_type' => $this->pixType,
            'pix_key' => $this->pixKey,
            'amount' => $this->amount,
            'error_reason' => $this->errorReason,
            'scheduled' => $this->scheduled,
            'scheduled_for' => $this->scheduledFor,
            'done' => $this->done,
            'error' => $this->error,
        ];
    }
}
