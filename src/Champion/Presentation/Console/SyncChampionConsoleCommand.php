<?php

declare(strict_types=1);

namespace App\Champion\Presentation\Console;

use App\Champion\Application\UseCase\SyncChampionUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:champion:sync',
    description: 'Sync champion from Riot Data Dragon API',
)]
final class SyncChampionConsoleCommand extends Command
{
    public function __construct(
        private readonly SyncChampionUseCase $syncChampionUseCase,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('champion', InputArgument::REQUIRED, 'Name champion (Jinx)')
            ->addArgument('version', InputArgument::REQUIRED, 'Game version (e.g. 15.1.1)')
            ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL, 'Locale for champion data', 'fr_FR');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $champion = $input->getArgument('champion');
        $version = $input->getArgument('version');
        $locale = $input->getOption('locale');

        try {
            $this->syncChampionUseCase->execute($champion, $version, $locale);

            $io->success(sprintf('Champion %s synced successfully for version %s', $champion, $version));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
