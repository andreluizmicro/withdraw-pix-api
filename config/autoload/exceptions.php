<?php

declare(strict_types=1);

use App\Infrastructure\Exception\Handler\AppExceptionHandler;
use App\Infrastructure\Exception\Handler\HttpValidationExceptionHandler;

return [
    'handler' => [
        'http' => [
            HttpValidationExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
