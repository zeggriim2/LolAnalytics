<?php

declare(strict_types=1);

namespace App\Champion\Presentation\Console;

use App\Champion\Application\Exception\ChampionValidationException;
use App\Champion\Application\UseCase\SyncChampionsUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:champions:sync',
    description: 'Sync champions from Riot Data Dragon API',
)]
final class SyncChampionsConsoleCommand extends Command
{
    public function __construct(
        private readonly SyncChampionsUseCase $syncChampionsUseCase,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('version', InputArgument::REQUIRED, 'Game version (e.g. 15.1.1)')
            ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL, 'Locale for champion data', 'fr_FR');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $version = $input->getArgument('version');
        $locale = $input->getOption('locale');

        try {
            $this->syncChampionsUseCase->execute($version, $locale);

            $io->success(sprintf('Champions synced successfully for version %s', $version));

            return Command::SUCCESS;
        } catch (ChampionValidationException $e) {
            $io->warning(sprintf('Sync completed with %d validation failure(s):', count($e->failuresByRiotId())));

            foreach ($e->failuresByRiotId() as $riotId => $messages) {
                $io->error(sprintf('[%s] %s', $riotId, implode(' | ', $messages)));
            }

            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
