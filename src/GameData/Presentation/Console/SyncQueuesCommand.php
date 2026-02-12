<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncQueueUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:queues',
    description: 'Synchronize queues from Riot static API',
)]
final class SyncQueuesCommand extends Command
{
    public function __construct(
        private readonly SyncQueueUseCase $syncQueueUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Queues from Riot API');

        try {
            $this->syncQueueUseCase->execute();

            $io->success('Queues synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Queues synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
