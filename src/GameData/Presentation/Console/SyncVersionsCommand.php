<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncVersionUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:versions',
    description: 'Synchronize game versions from Riot static API',
)]
final class SyncVersionsCommand extends Command
{
    public function __construct(
        private readonly SyncVersionUseCase $syncVersionUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Versions from Riot API');

        try {
            $this->syncVersionUseCase->execute();

            $io->success('Versions synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Versions synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
