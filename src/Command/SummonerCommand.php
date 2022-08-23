<?php

namespace App\Command;

use App\Manager\SummonerManager;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:summoner',
    description: 'Add a short description for your command',
)]
class SummonerCommand extends Command
{
    public function __construct(
        private SummonerManager $summonerManager,
        private SummonerApi $summonerApi,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de l\'invocateur')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $summonerApi = $this->summonerApi->summonerBySummonerName($name);

        $error = $this->summonerManager->enregistrer($summonerApi);

        $io->success("L'invocateur {$name} à bien été ajouté à la base de donnée. (Id Lol : {$summonerApi->getId()} )");

        return Command::SUCCESS;
    }
}
