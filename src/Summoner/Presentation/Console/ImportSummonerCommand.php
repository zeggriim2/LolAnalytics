<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Console;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerCommand as AppImportSummonerCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:summoner:import',
    description: 'Import a summoner by PUUID',
)]
final class ImportSummonerCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('puuid', InputArgument::REQUIRED, 'The summoner PUUID')
            ->addArgument('platform', InputArgument::REQUIRED, 'The platform (e.g., euw1, na1, kr)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $puuid = $input->getArgument('puuid');
        $platformValue = $input->getArgument('platform');

        try {
            $platform = Platform::from($platformValue);
        } catch (\ValueError) {
            $io->error(sprintf('Invalid platform "%s". Valid platforms: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))));

            return Command::FAILURE;
        }

        $io->info(sprintf('Importing summoner with PUUID: %s on platform: %s', $puuid, $platform->value));

        try {
            $this->commandBus->dispatch(new AppImportSummonerCommand($puuid, $platform));
            $io->success('Summoner imported successfully!');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error(sprintf('Failed to import summoner: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
