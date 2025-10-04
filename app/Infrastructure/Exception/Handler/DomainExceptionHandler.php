<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception\Handler;

use App\Domain\Exception\DomainError;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Swow\Psr7\Message\ResponsePlusInterface;
use Throwable;

class DomainExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponsePlusInterface $response): ResponsePlusInterface
    {
        var_dump($throwable);

        $this->stopPropagation();

        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/json')
            ->setBody(new SwooleStream(json_encode([
                'message' => $throwable->getMessage(),
            ])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof DomainError;
    }
}

