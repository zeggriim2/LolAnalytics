<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\UseCase\IngestMatchUseCase;
use App\Match\Domain\ValueObjet\Region;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:match:ingest',
    description: 'Ingest match',
)]
final class IngestMatchCommand extends Command
{
    public function __construct(
        private readonly IngestMatchUseCase $ingestMatchUseCase,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('matchId', InputArgument::REQUIRED)
            ->addArgument('region', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $matchId = $input->getArgument('matchId');
        $region = $input->getArgument('region');

        try {
            $region = Region::from($region);
            $this->ingestMatchUseCase->execute($matchId, $region);

            $io->success(sprintf('Ingest match %s successfully', $matchId));

            return Command::SUCCESS;
        } catch (\Exception $e) {

            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
