<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Swoole\Http\Status;
use Swow\Psr7\Message\ResponsePlusInterface;
use Throwable;

class HttpValidationExceptionHandler extends ExceptionHandler
{
    public function __construct(protected HttpResponse $response)
    {
    }

    public function handle(Throwable $throwable, ResponsePlusInterface $response): ResponsePlusInterface
    {
        error_log("HttpValidationExceptionHandler got: " . get_class($throwable));
        $this->stopPropagation();

        /** @var ValidationException $throwable */
        return $response
            ->setStatus(Status::UNPROCESSABLE_ENTITY)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(new SwooleStream(json_encode([
                'message' => 'Validation failed',
                'errors' => $throwable->validator->errors()->messages(),
            ])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}