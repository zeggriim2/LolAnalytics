<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncGameDataUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync',
    description: 'Synchronize game data from Riot static API (queues, game modes, game types, maps, seasons)',
)]
final class SyncGameDataCommand extends Command
{
    public function __construct(
        private readonly SyncGameDataUseCase $syncGameDataUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Game Data from Riot API');

        try {
            $this->syncGameDataUseCase->execute();

            $io->success('Synchronization completed successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
