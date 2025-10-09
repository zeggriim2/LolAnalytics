<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\UseCase\ListMatchesUseCase;
use App\Match\Domain\Model\Matche;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
    )
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        try {
            $matches = $this->listMatchesUseCase->execute();
            $headers = ['Match ID', 'Game Id', 'PlayedAt', 'Durer de la partie'];
            $dataMatch = [];

            /** @var Matche $match */
            foreach ($matches as $match) {
                $dataMatch[] = [
                    $match->id(),
                    $match->gameId()->value(),
                    $match->playedAt()->format('d-m-Y H:i:s'),
                    $this->formatDuration($match->durationSeconds())
                ];
            }
            $io->table($headers, $dataMatch);
            $io->success(sprintf('List match successfully'));

            return Command::SUCCESS;
        } catch (\Exception $e) {

            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Convertit une durée totale en secondes vers un format "mm:ss"
     */
    private function formatDuration(int $totalSeconds): string
    {
        $minutes = intdiv($totalSeconds, 60);
        $seconds = $totalSeconds % 60;

        // Format avec zéro devant si nécessaire, ex : 05:09
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
