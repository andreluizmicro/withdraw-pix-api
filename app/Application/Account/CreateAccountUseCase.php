<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\DTO\Account\CreateAccountInputDTO;
use App\Domain\Repository\Account\AccountRepositoryInterface;

class CreateAccountUseCase
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function execute(CreateAccountInputDTO $input): void
    {
        var_dump($input);
        die();
    }
}
