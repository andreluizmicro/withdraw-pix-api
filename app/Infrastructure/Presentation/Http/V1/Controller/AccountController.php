<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\Http\V1\Controller;

use App\Application\Account\CreateAccountUseCase;
use App\Infrastructure\Presentation\Http\V1\Request\CreateAccountRequest;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Throwable;

class AccountController
{
    public function __construct(
        private readonly CreateAccountUseCase $createAccountUseCase
    ) {
    }

    public function create(CreateAccountRequest $request, Response $response): ResponseInterface
    {
        try {
            $this->createAccountUseCase->execute($request->toDto());

            return $response->withStatus(201);
        } catch (Throwable $exception) {
            return $response
                ->json(['message' => $exception->getMessage()])
                ->withStatus(Status::UNPROCESSABLE_ENTITY);
        }
    }
}
