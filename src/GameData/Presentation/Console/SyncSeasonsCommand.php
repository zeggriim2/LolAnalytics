<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncSeasonUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:seasons',
    description: 'Synchronize seasons from Riot static API',
)]
final class SyncSeasonsCommand extends Command
{
    public function __construct(
        private readonly SyncSeasonUseCase $syncSeasonUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Seasons from Riot API');

        try {
            $this->syncSeasonUseCase->execute();

            $io->success('Seasons synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Seasons synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
