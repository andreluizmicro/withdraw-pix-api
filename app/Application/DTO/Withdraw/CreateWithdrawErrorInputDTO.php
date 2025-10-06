<?php

declare(strict_types=1);

namespace App\Application\DTO\Withdraw;

class CreateWithdrawErrorInputDTO
{
    public function __construct(
        public string $id,
        public string $accountId,
        public string $method,
        public float $amount,
        public string $errorReason,
        public ?bool $scheduled = false,
        public ?string $scheduledFor = null,
        public ?bool $done = false,
        public ?bool $error = true,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'accountId' => $this->accountId,
            'method' => $this->method,
            'amount' => $this->amount,
            'scheduled' => $this->scheduled,
            'scheduledFor' => $this->scheduledFor,
            'done' => $this->done,
            'error' => $this->error,
            'errorReason' => $this->errorReason,
        ];
    }
}
