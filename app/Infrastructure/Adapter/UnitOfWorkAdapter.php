<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Adapter\UnitOfWorkAdapterInterface;
use Hyperf\DbConnection\Db;

class UnitOfWorkAdapter implements UnitOfWorkAdapterInterface
{

    public function begin(): void
    {
        Db::beginTransaction();
    }

    public function commit(): void
    {
        Db::commit();
    }

    public function rollback(): void
    {
        Db::rollback();
    }
}