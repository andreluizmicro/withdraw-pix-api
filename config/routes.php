<?php

declare(strict_types=1);

use App\Infrastructure\Presentation\Http\V1\Controller\AccountController;
use Hyperf\HttpServer\Router\Router;

Router::get('/health', fn () => [
    'message' => 'Alive and kicking',
    'time' => date(DATE_ATOM),
]);


Router::addGroup('/api/v1', function () {
    Router::addGroup('/account', function () {
        Router::post('/', [AccountController::class, 'create']);
       Router::post('{accountId}/balance/withdraw', 'App\Controller\AccountController@withdraw');
    });
});