<?php

declare(strict_types=1);

namespace App\Domain\Adapter;

interface UnitOfWorkAdapterInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
