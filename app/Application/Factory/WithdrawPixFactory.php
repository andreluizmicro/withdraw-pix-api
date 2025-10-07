<?php

namespace App\Application\Factory;

use App\Application\DTO\Withdraw\CreateWithdrawInputDTO;
use App\Domain\Entity\AccountWithdraw;
use App\Domain\Entity\AccountWithDrawPix;
use App\Domain\Exception\UuidException;
use App\Domain\Exception\WithDrawPixException;
use App\Domain\ValueObject\PixKey;
use App\Domain\ValueObject\PixType;
use App\Domain\ValueObject\Uuid;

final class WithdrawPixFactory
{
    /**
     * @throws UuidException
     * @throws WithDrawPixException
     */
    public static function create(CreateWithdrawInputDTO $input, AccountWithdraw $withdraw): AccountWithdrawPix
    {
        $pixType = new PixType($input->pixType);

        return new AccountWithdrawPix(
            id: Uuid::random(),
            accountWithdrawId: $withdraw->id(),
            type: $pixType,
            key: new PixKey($pixType, $input->pixKey),
        );
    }
}