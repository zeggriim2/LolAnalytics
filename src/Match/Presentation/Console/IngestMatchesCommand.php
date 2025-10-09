<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\UseCase\IngestMatchesUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:matches:ingest',
    description: 'Ingest matches',
)]
final class IngestMatchesCommand extends Command
{
    public function __construct(
        private readonly IngestMatchesUseCase $ingestMatchesUseCase
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this->addArgument('puuid', InputArgument::REQUIRED)
            ->addArgument('region', InputArgument::OPTIONAL, 'region', 'europe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $puuid = $input->getArgument('puuid');
        $region = $input->getArgument('region');

        try {
            $this->ingestMatchesUseCase->execute($puuid, $region);

            $io->success(sprintf('Ingest match %s successfully', $puuid));

            return Command::SUCCESS;
        } catch (\Exception $e) {

            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
