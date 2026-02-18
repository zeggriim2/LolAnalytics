<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\ReadModel\MatchReadModel;
use App\Match\Application\UseCase\ListMatchesUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:matches:list',
    description: 'list match',
)]
final class ListMatchesCommand extends Command
{
    public function __construct(
        private readonly ListMatchesUseCase $listMatchesUseCase
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('page', 'p', InputOption::VALUE_OPTIONAL, 'Page number', 1)
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Items per page', 20);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $page = (int) $input->getOption('page');
            $limit = (int) $input->getOption('limit');

            $result = $this->listMatchesUseCase->execute($page, $limit);

            $headers = ['Match ID', 'Game Id', 'PlayedAt', 'Durer de la partie'];
            $dataMatch = [];

            /** @var MatchReadModel $match */
            foreach ($result->items as $match) {
                $dataMatch[] = [
                    $match->id,
                    $match->gameId,
                    $match->playedAt->format('d-m-Y H:i:s'),
                    $match->durationFormatted,
                ];
            }
            $io->table($headers, $dataMatch);
            $io->info(sprintf('Page %d/%d (total: %d)', $result->page, $result->totalPages, $result->total));
            $io->success('List match successfully');

            return Command::SUCCESS;
        } catch (\Exception $e) {

            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
