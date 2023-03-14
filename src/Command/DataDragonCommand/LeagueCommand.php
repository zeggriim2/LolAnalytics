<?php

namespace App\Command\DataDragonCommand;

use App\Entity\DivisionLeague;
use App\Entity\League;
use App\Entity\QueueLeague;
use App\Entity\Summoner;
use App\Entity\TierLeague;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiLeague\Enum\League\Division;
use Zeggriim\RiotApiLeague\Enum\League\Queue;
use Zeggriim\RiotApiLeague\Enum\League\Tier;
use Zeggriim\RiotApiLeague\Model\League\LeagueDto;
use Zeggriim\RiotApiLeague\Model\Summoner\SummonerDto;
use Zeggriim\RiotApiLeague\Riot\League\LeagueApi;
use Zeggriim\RiotApiLeague\Riot\Summoner\SummonerApi;

#[AsCommand(
    name: 'app:league',
    description: 'Initialise les données pour les Leagues',
)]
final class LeagueCommand extends Command
{

    public function __construct(
        private ManagerRegistry $managerRegistry,
        private LeagueApi $leagueApi,
        private SummonerApi $summonerApi
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                name: "queue",
                mode: InputArgument::OPTIONAL,
                description: "Queue",
                default: Queue::RANKED_SOLO
            )
            ->addOption(
                name: "tier",
                mode: InputArgument::OPTIONAL,
                description: "Tier",
                default: Tier::IRON
            )
            ->addOption(
                name: "division",
                mode: InputArgument::OPTIONAL,
                description: "Division",
                default: Division::IV
            )
            ->addOption(
                name:"limit",
                mode: InputArgument::OPTIONAL,
                description: "Limite du nombre d'éléments",
                default: 25
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countAddLeague = (int)$input->getOption('limit');
        $queueLeague =
            $this->managerRegistry->getRepository(QueueLeague::class)
                ->findOneBy(['code' => $input->getOption('queue')]);

        if(is_null($queueLeague)){
            $io->error('Queue League introuvable');
            return Command::FAILURE;
        }

        $divisionLeague =
            $this->managerRegistry->getRepository(DivisionLeague::class)
                ->findOneBy(['code' => $input->getOption('division')]);
        if(is_null($divisionLeague)){
            $io->error('Division League introuvable');
            return Command::FAILURE;
        }

        $tierLeague =
            $this->managerRegistry->getRepository(TierLeague::class)
                ->findOneBy(['code' => $input->getOption('tier')]);
        if(is_null($tierLeague)){
            $io->error('Tier League introuvable');
            return Command::FAILURE;
        }


        // Récupération les leagues
        $leaguesApi =
            $this->leagueApi->getLeague($queueLeague->getCode(), $tierLeague->getCode(), $divisionLeague->getCode());

        $io->progressStart($countAddLeague);
        $countLimit = 0;
        foreach ($leaguesApi as $leagueDto) {
            if($countAddLeague < $countLimit){
                break;
            }
            $league =
                $this->managerRegistry->getRepository(League::class)
                    ->findLeaguesCreatedWithinLastDay($leagueDto);

            if(count($league) === 0) {
                $io->progressAdvance();
                $countLimit++;
                $league = $this->createLeague(
                    leagueDto: $leagueDto,
                    divisionLeague: $divisionLeague,
                    tierLeague: $tierLeague,
                    queueLeague: $queueLeague
                );

                $summoner = $this->managerRegistry->getRepository(Summoner::class)->findOneBy(
                    ['idLol' => $leagueDto->getSummonerId()]
                );

                if($summoner === null){
                    $summonerApi = $this->summonerApi->getSummonerBySummonerId($leagueDto->getSummonerId());
                    if($summonerApi){
                        $summoner = $this->createSummoner($summonerApi);
                        $this->managerRegistry->getManager()->persist($summoner);
                    }
                }

                $summoner->addLeague($league);

                $this->managerRegistry->getManager()->persist($league);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("$countAddLeague leagues ont été ajouté");
        return Command::SUCCESS;
    }


    private function createLeague(
        LeagueDto $leagueDto,
        DivisionLeague $divisionLeague,
        TierLeague $tierLeague,
        QueueLeague $queueLeague
    ): League
    {
        return (new League)
            ->setLeagueId($leagueDto->getLeagueId())
            ->setLeaguePoints($leagueDto->getLeaguePoints())
            ->setLooses($leagueDto->getLosses())
            ->setWins($leagueDto->getWins())
            ->setInactive($leagueDto->isInactive())
            ->setFreshBlood($leagueDto->isFreshBlood())
            ->setHotStreak($leagueDto->isHotStreak())
            ->setVeteran($leagueDto->isVeteran())
            ->setDivisionLeague($divisionLeague)
            ->setTierLeague($tierLeague)
            ->setQueueLeague($queueLeague)
        ;
    }

    private function createSummoner(
        SummonerDto $summonerDto
    ): Summoner
    {
        return (new Summoner())
            ->setName($summonerDto->getName())
            ->setIdLol($summonerDto->getId())
            ->setPuuid($summonerDto->getPuuid())
            ->setAccountId($summonerDto->getAccountId())
            ->setSumonerLevel($summonerDto->getSummonerLevel())
            ->setRevisionDate(
                (new \DateTimeImmutable())->setTimestamp($summonerDto->getRevisionDate() / 1000)
            )
            ->setProfileIconId($summonerDto->getProfileIconId())
            ;
    }
}
