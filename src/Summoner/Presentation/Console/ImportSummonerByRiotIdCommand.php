<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Console;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerByRiotIdCommand as AppImportSummonerByRiotIdCommand;
use App\Summoner\Application\Exception\SummonerValidationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:summoner:import-by-riot-id',
    description: 'Import a summoner by Riot ID (GameName#TagLine)',
)]
final class ImportSummonerByRiotIdCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('riot-id', InputArgument::REQUIRED, 'The Riot ID (format: GameName#TagLine)')
            ->addArgument('platform', InputArgument::REQUIRED, 'The platform (e.g., euw1, na1, kr)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $riotId = $input->getArgument('riot-id');
        $platformValue = $input->getArgument('platform');

        $parts = explode('#', $riotId, 2);

        if (2 !== count($parts)) {
            $io->error('Invalid Riot ID format. Expected: GameName#TagLine');

            return Command::FAILURE;
        }

        [$gameName, $tagLine] = $parts;

        try {
            $platform = Platform::from($platformValue);
        } catch (\ValueError) {
            $io->error(sprintf('Invalid platform "%s". Valid platforms: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))));

            return Command::FAILURE;
        }

        $io->info(sprintf('Importing summoner %s#%s on platform: %s', $gameName, $tagLine, $platform->value));

        try {
            $this->commandBus->dispatch(new AppImportSummonerByRiotIdCommand($gameName, $tagLine, $platform));
            $io->success('Summoner imported successfully!');

            return Command::SUCCESS;
        } catch (SummonerValidationException $e) {
            foreach ($e->failuresByIdentifier() as $identifier => $messages) {
                $io->error(sprintf('[%s] %s', $identifier, implode(' | ', $messages)));
            }

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $io->error(sprintf('Failed to import summoner: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
