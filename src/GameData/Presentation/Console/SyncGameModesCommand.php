<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncGameModeUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:game-modes',
    description: 'Synchronize game modes from Riot static API',
)]
final class SyncGameModesCommand extends Command
{
    public function __construct(
        private readonly SyncGameModeUseCase $syncGameModeUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Game Modes from Riot API');

        try {
            $this->syncGameModeUseCase->execute();

            $io->success('Game Modes synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Game Modes synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
