<?php

declare(strict_types=1);

use Hyperf\View\Engine\BladeEngine;
use Hyperf\View\Mode;

return [
    'engine' => BladeEngine::class,
    'mode' => Mode::SYNC,
    'config' => [
        'view_path' => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/runtime/view/',
    ],
];
