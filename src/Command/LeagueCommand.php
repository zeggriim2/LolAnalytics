<?php

namespace App\Command;

use App\Entity\Invocateur;
use App\Entity\League;
use App\Manager\SummonerManager;
use App\Repository\InvocateurRepository;
use App\Repository\LeagueRepository;
use App\Services\API\LOL\DataDragon\Division;
use App\Services\API\LOL\DataDragon\Tier;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\LeagueApi;
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
    name: 'app:league',
    description: 'Add a short description for your command',
)]
class LeagueCommand extends Command
{
    public function __construct(
        private InvocateurRepository $invocateurRepository,
        private SummonerApi $summonerApi,
        private EntityManagerInterface $doctrine,
        private LeagueApi $leagueApi,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('division', InputArgument::REQUIRED, 'Division de la League')
            ->addArgument('tier', InputArgument::REQUIRED, 'Division de la League')
            ->addArgument('queue', InputArgument::OPTIONAL, 'Division de la League', 'RANKED_SOLO_5x5')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $division = $input->getArgument('division');
        $tier = $input->getArgument('tier');
        $queue = $input->getArgument('queue');


        if(!in_array($division, Division::ALL_DIVISION) || !in_array($tier, Tier::ALL_TIERS)) {
            $io->error("Division ou Tier incorrect");
            return Command::INVALID;
        }
        $leagues = $this->leagueApi->leagueByQueueByTierByDivision($queue, $tier, $division);

        foreach ($leagues as $league) {
            // On vérifie si la league à déjà été intégré
            if(
                $this->doctrine->getRepository(LeagueRepository::class)
                    ->findOneBy(['leagueId' => $league->getLeagueId()]) === null
            ){
                $invocateur = $this->invocateurRepository->findOneBy(['idLol' => $league->getSummonerId()]);
                if($invocateur === null) {
                    $invocateur = $this->invocateur($league->getSummonerName());
                }

                if($invocateur) {
                    $this->league($invocateur, $league);
                }
            }
        }

        $this->doctrine->flush();

        return Command::SUCCESS;
    }

    private function league(
        Invocateur $invocateur,
        LeagueEntryDTO $leagueEntryDto
    )
    {
        $league = (new League())
            ->setLeagueId($leagueEntryDto->getLeagueId())
            ->setLeaguePoints($leagueEntryDto->getLeaguePoints())
            ->setFreshBlood($leagueEntryDto->isFreshBlood())
            ->setInactive($leagueEntryDto->isInactive())
            ->setHotStreak($leagueEntryDto->isHotStreak())
            ->setVeteran($leagueEntryDto->isVeteran())
            ->setWins($leagueEntryDto->getWins())
            ->setLosses($leagueEntryDto->getLosses())
            ->setInvocateur($invocateur)
            ;

        $this->doctrine->persist($league);
    }

    private function invocateur(string $nameInvocateur): ?Invocateur
    {
        $summonerApi = $this->summonerApi->summonerBySummonerName($nameInvocateur);
        if($summonerApi) {
            $invocateur = (new Invocateur())
                ->setName($summonerApi->getName())
                ->setIdLol($summonerApi->getId())
                ->setAccoundId($summonerApi->getAccountId())
                ->setPuuid($summonerApi->getPuuid())
                ->setProfileIconId($summonerApi->getProfileIconId())
                ->setSummonerLevel($summonerApi->getSummonerLevel())
            ;

            $this->doctrine->persist($invocateur);
            return $invocateur;
        }
        return null;
    }
}
