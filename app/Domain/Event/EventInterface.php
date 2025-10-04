<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventInterface
{
    public function getName(): string;

    public function getCategory(): string;

    public function getProperties(): array;
}
