<?php

declare(strict_types=1);

namespace App\Infrastructure\Presentation\Http\V1\Controller;

use App\Application\UseCase\Account\CreateAccountUseCase;
use App\Application\UseCase\Withdraw\WithdrawFundsUseCase;
use App\Infrastructure\Presentation\Http\V1\Request\CreateAccountRequest;
use App\Infrastructure\Presentation\Http\V1\Request\WithdrawRequest;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Throwable;

class AccountController
{
    public function __construct(
        private readonly CreateAccountUseCase $createAccountUseCase,
        private readonly WithdrawFundsUseCase $withdrawFundsUseCase,
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

    public function withdraw(WithdrawRequest $request, Response $response): ResponseInterface
    {
        try {
            $this->withdrawFundsUseCase->execute($request->toDto());

            return $response->withStatus(201);
        } catch (Throwable $exception) {
            return $response
                ->json(['message' => $exception->getMessage()])
                ->withStatus(Status::UNPROCESSABLE_ENTITY);
        }
    }
}
