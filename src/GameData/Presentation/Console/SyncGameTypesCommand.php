<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncGameTypeUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:game-types',
    description: 'Synchronize game types from Riot static API',
)]
final class SyncGameTypesCommand extends Command
{
    public function __construct(
        private readonly SyncGameTypeUseCase $syncGameTypeUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Game Types from Riot API');

        try {
            $this->syncGameTypeUseCase->execute();

            $io->success('Game Types synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Game Types synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
