<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\UseCase\IngestMatchesUseCase;
use App\Match\Domain\ValueObjet\Region;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    public function configure(): void
    {
        $this->addArgument('puuid', InputArgument::REQUIRED)
            ->addArgument('region', InputArgument::OPTIONAL, 'region', 'europe')
            ->addOption('count', 'c', InputOption::VALUE_OPTIONAL, 'Number of matches to fetch', 20)
            ->addOption('start', 's', InputOption::VALUE_OPTIONAL, 'Start index', 0)
            ->addOption('start-time', null, InputOption::VALUE_OPTIONAL, 'Start time (Y-m-d or timestamp)')
            ->addOption('end-time', null, InputOption::VALUE_OPTIONAL, 'End time (Y-m-d or timestamp)')
            ->addOption('queue', 'qu', InputOption::VALUE_OPTIONAL, 'Queue ID')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'Match type (ranked, normal, tourney, tutorial)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $puuid = $input->getArgument('puuid');
        $region = $input->getArgument('region');
        $count = (int) $input->getOption('count');
        $start = (int) $input->getOption('start');
        $startTime = $this->parseDateTime($input->getOption('start-time'));
        $endTime = $this->parseDateTime($input->getOption('end-time'));
        $queue = null !== $input->getOption('queue') ? (int) $input->getOption('queue') : null;
        $type = $input->getOption('type');

        try {
            $region = Region::from($region);
            $this->ingestMatchesUseCase->execute(
                $puuid,
                $region,
                $count,
                $start,
                $startTime,
                $endTime,
                $queue,
                $type,
            );

            $io->success(sprintf('Ingest matches for %s successfully', $puuid));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function parseDateTime(?string $value): ?\DateTimeImmutable
    {
        if (null === $value) {
            return null;
        }

        if (is_numeric($value)) {
            return (new \DateTimeImmutable())->setTimestamp((int) $value);
        }

        return new \DateTimeImmutable($value);
    }
}
