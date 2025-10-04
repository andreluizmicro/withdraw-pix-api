<?php

declare(strict_types=1);

use App\Infrastructure\Presentation\Http\V1\Controller\AccountController;
use App\Infrastructure\Presentation\Http\V1\Controller\AccountWithDrawPixController;
use Hyperf\HttpServer\Router\Router;

Router::get('/health', fn () => [
    'message' => 'Alive and kicking',
    'time' => date(DATE_ATOM),
]);


Router::addGroup('/v1', function () {
    Router::post('/account', [AccountController::class, 'create']);
    Router::post('/account/{accountId}/balance/withdraw', [AccountController::class, 'withdraw']);
    Router::post('/{accountId}/withdraw/pix', [AccountWithDrawPixController::class, 'store']);
});