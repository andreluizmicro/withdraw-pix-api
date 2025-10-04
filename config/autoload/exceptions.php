<?php

declare(strict_types=1);

use App\Infrastructure\Exception\Handler\AppExceptionHandler;
use App\Infrastructure\Exception\Handler\DomainExceptionHandler;
use App\Infrastructure\Exception\Handler\HttpValidationExceptionHandler;
use Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler;

return [
    'handler' => [
        'http' => [
            HttpValidationExceptionHandler::class,
            HttpExceptionHandler::class,
            DomainExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
