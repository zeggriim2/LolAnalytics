<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Console;

use App\GameData\Application\UseCase\SyncMapUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:gamedata:sync:maps',
    description: 'Synchronize maps from Riot static API',
)]
final class SyncMapsCommand extends Command
{
    public function __construct(
        private readonly SyncMapUseCase $syncMapUseCase,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Synchronizing Maps from Riot API');

        try {
            $this->syncMapUseCase->execute();

            $io->success('Maps synchronized successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Maps synchronization failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
