<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Domain\Repository\Withdraw\WithdrawRepositoryInterface;
use App\Infrastructure\Broker\RabbitMQ\Producer\SendScheduledPixToQueueProducer;
use Hyperf\Command\Command;
use Throwable;

class ExecuteSchedulePixCommand extends Command
{
    private const COMMAND_NAME = 'pix:execute-scheduled';

    public function __construct(
        private readonly WithdrawRepositoryInterface $withdrawRepository,
        private readonly SendScheduledPixToQueueProducer $producer,
    ) {
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure(): void
    {
        $this->setDescription('Executa todos os PIX agendados prontos para processamento');
    }

    public function handle(): void
    {
        $this->line('🚀 Iniciando execução dos PIX agendados...');

        try {
            $schedules = $this->withdrawRepository->findScheduledPix();

            foreach ($schedules as $schedule) {
                $this->producer->produce(
                    payload: ['account_withdraw_id' => $schedule->id()->value],
                );
            }

            $this->info('✅ PIX agendados executados com sucesso.');
        } catch (Throwable $e) {
            $this->error('❌ Erro ao executar PIX agendados: ' . $e->getMessage());
        }
    }
}
