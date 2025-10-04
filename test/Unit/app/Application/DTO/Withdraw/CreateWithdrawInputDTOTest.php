<?php

declare(strict_types=1);

namespace HyperfTest\Unit\app\Application\DTO\Withdraw;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use PHPUnit\Framework\TestCase;

class CreateWithdrawInputDTOTest extends TestCase
{
    public function testCreateWithdrawInputDTO(): void
    {
        $data = [
            'account_id' => '67ef9208-eada-408b-bdd1-8ca126c78416',
            'method' => 'PIX',
            'pix' => [
                'type' =>  'email',
                'key' => 'andreluizmicro@gmail.com',
            ],
            'amount' => 10_000000,
            'schedule' => null,
        ];

        $dto = CreateWithdrawInputDTO::fromArray($data);

        $this->assertEquals($data['account_id'], $dto->accountId);
        $this->assertEquals($data['method'], $dto->method);
        $this->assertEquals($data['pix']['type'], $dto->pixType);
        $this->assertEquals($data['pix']['key'], $dto->pixKey);
        $this->assertEquals($data['schedule'], $dto->schedule);
    }
}
