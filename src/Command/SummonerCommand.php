<?php

namespace App\Command;

use App\Entity\Invocateur;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use Doctrine\ORM\EntityManagerInterface;
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
        private SummonerApi $summonerApi,
        private EntityManagerInterface $doctrine,
        string $name = null
    )
    {
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

        if($summonerApi === null){
            $io->warning('Nom d\'utilisateur introuvable');
            return Command::FAILURE;
        } elseif ($this->doctrine->getRepository(Invocateur::class)->findOneBy(['idLol' => $summonerApi->getId()])) {
            $io->warning('Nom d\'utilisateur déjà existant');
            return Command::FAILURE;
        }

        $invocateur = (new Invocateur())
            ->setName($summonerApi->getName())
            ->setIdLol($summonerApi->getId())
            ->setAccoundId($summonerApi->getAccountId())
            ->setPuuid($summonerApi->getPuuid())
            ->setProfileIconId($summonerApi->getProfileIconId())
            ->setSummonerLevel($summonerApi->getSummonerLevel())
        ;

        $this->doctrine->persist($invocateur);
        $this->doctrine->flush();

        $io->success("L'invocateur {$name} à bien été ajouté à la base de donnée. (Id Lol : {$summonerApi->getId()} )");
        return Command::SUCCESS;
    }
}
