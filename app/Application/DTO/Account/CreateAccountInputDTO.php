<?php

declare(strict_types=1);

namespace App\Application\DTO\Account;

 readonly class CreateAccountInputDTO
{
    public function __construct(
      public string $name,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new CreateAccountInputDTO(
            name: $data['name'],
        );
    }
}
