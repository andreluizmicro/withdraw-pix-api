<?php

declare(strict_types=1);

namespace App\Domain\Exception\Handler\Account;

use App\Domain\Exception\PersistenceErrorException;

class AccountNotFoundException extends PersistenceErrorException
{
    public function __construct() {
        parent::__construct('Account not found');
    }
}
