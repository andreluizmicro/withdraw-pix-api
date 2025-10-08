<?php

declare(strict_types=1);

namespace Tests\Unit\app\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\Exception\BalanceException;
use App\Domain\Exception\DepositException;
use App\Domain\Exception\NameException;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithdrawException;
use App\Domain\ValueObject\Balance;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws Exception
     */
    public function testAttributes(): void
    {
        $uuid = '201ab977-585c-403c-8412-705f40964ed5';
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();
        $account = $this->getAccountMockData([
            'id' => $uuid,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $this->assertInstanceOf(Uuid::class, $account->id());
        $this->assertEquals(new Uuid($uuid), $account->id());
        $this->assertEquals('JOHN DOE', strtoupper($account->name()->value));
        $this->assertEquals(1000.0, $account->balance()->value);
        $this->assertTrue($account->isActive());
        $this->assertEquals($createdAt, $account->createdAt());
        $this->assertEquals($updatedAt, $account->updatedAt());
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     */
    public function testShouldActive(): void
    {
        $account = $this->getAccountMockData(['is_active' => false]);

        $this->assertFalse($account->isActive());

        $account->activate();

        $this->assertTrue($account->isActive());

        $account->deactivate();

        $this->assertFalse($account->isActive());
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws DepositException
     */
    public function testShouldDepositSuccessfully(): void
    {
        $account = $this->getAccountMockData(['balance' => 500.0]);

        $account->deposit(200.0);

        $this->assertEquals(700.0, $account->balance()->value);
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws DepositException
     */
    public function testShouldThrowExceptionWhenInvalidDepositAmount(): void
    {
        $this->expectException(DepositException::class);
        $this->expectExceptionMessage('Deposit amount must be at least R$ 0,01');

        $account = $this->getAccountMockData();
        $account->deposit(0.0);
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws WithdrawException
     * @throws BalanceException
     */
    public function testShouldSubtractBalanceSuccessfully(): void
    {
        $account = $this->getAccountMockData(['balance' => 1000.0]);

        $account->subtract(300.0);

        $this->assertEquals(700.0, $account->balance()->value);
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws NameException
     */
    public function testShouldThrowExceptionWhenInvalidSubtractAmount(): void
    {
        $this->expectException(WithdrawException::class);
        $this->expectExceptionMessage('WithDraw amount cannot be negative or zero');

        $account = $this->getAccountMockData();
        $account->subtract(-100.0);
    }

    /**
     * @throws UuidException
     * @throws BalanceException
     * @throws NameException
     */
    public function testShouldThrowExceptionWhenInsufficientBalance(): void
    {
        $this->expectException(WithdrawException::class);
        $this->expectExceptionMessage('Insufficient balance for withdrawal');

        $account = $this->getAccountMockData(['balance' => 100.0]);
        $account->subtract(200.0);
    }

    /**
     * @throws UuidException
     * @throws NameException
     * @throws BalanceException
     * @throws Exception
     */
    private function getAccountMockData(array $attributes = []): Account
    {
        return new Account(
            id: new Uuid($attributes['id'] ?? '201ab977-585c-403c-8412-705f40964ed5'),
            name: new Name($attributes['name'] ?? 'John Doe'),
            balance: new Balance($attributes['balance'] ?? 1000.0),
            isActive: $attributes['is_active'] ?? true,
            createdAt: $attributes['created_at'] ?? null,
            updatedAt: $attributes['updated_at'] ?? null,
        );
    }
}
