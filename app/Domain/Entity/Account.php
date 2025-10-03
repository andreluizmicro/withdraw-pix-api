<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Exception\DepositException;
use App\Domain\Exception\WithdrawException;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Exception;

class Account
{
    private const MIN_DEPOSIT_AMOUNT = 0.01;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly Uuid $id,
        private readonly Name $name,
        private readonly Balance $balance,
        private ?bool $isActive = true,
        private readonly ?DateTimeImmutable $createdAt = null,
        private readonly ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function balance(): Balance
    {
        return $this->balance;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @throws DepositException
     */
    public function deposit(float $amount): void
    {
        if ($amount < self::MIN_DEPOSIT_AMOUNT) {
            throw DepositException::depositCannotBeNegative();
        }

        $this->balance->value += $amount;
    }

    /**
     * @throws WithdrawException
     */
    public function subtract(float $amount): void
    {
        if ($amount <= Balance::MIN_VALUE) {
            throw WithdrawException::cannotBeNegative();
        }

        if ($amount > $this->balance->value) {
            throw WithdrawException::insufficientBalance();
        }

        $this->balance->value -= $amount;
    }
}
