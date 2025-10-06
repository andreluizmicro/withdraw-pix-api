<?php

declare(strict_types=1);

use App\Infrastructure\Command\ExecuteSchedulePixCommand;
use Hyperf\Crontab\Crontab;

return [
    'enable' => true,
    'crontab' => [
        (new Crontab())
            ->setName('pix-scheduled-executor')
            ->setRule('0 * * * *')
            ->setCallback([ExecuteSchedulePixCommand::class, 'handle'])
    ],
];
