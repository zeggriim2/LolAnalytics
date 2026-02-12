<?php

declare(strict_types=1);

namespace App\Match\Presentation\Console;

use App\Match\Application\UseCase\GetMatchDetailsUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:match:detail',
    description: 'list match',
)]
final class DetailMatchCommand extends Command
{
    public function __construct(
        private readonly GetMatchDetailsUseCase $getMatchUseCase
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this->addArgument('matchId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $matchId = $input->getArgument('matchId');

        try {
            $match = $this->getMatchUseCase->execute($matchId);

            if (!$match) {
                $io->error('Match not found');

                return Command::FAILURE;
            }

            $io->success((string) $match->id());

            $headers = ['Summoner', 'Champion Id', 'Win', 'Kills', 'Deaths', 'Assists'];
            $dataParticipants = [];

            foreach ($match->participants() as $participant) {
                $dataParticipants[] = [
                    $participant->summonerPuuid(),
                    $participant->championId(),
                    $participant->win() ? '✅' : '❌',
                    $participant->kda()->kills(),
                    $participant->kda()->deaths(),
                    $participant->kda()->assists(),
                ];
            }
            $io->table($headers, $dataParticipants);
            $io->success(sprintf('Detail match successfully'));

            return Command::SUCCESS;
        } catch (\Exception $e) {

            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
