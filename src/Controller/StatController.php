<?php

namespace App\Controller;

use App\Entity\Baron;
use App\Entity\Dragon;
use App\Entity\HistoriqueLeague;
use App\Entity\Inhibitor;
use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Entity\RiftHerald;
use App\Entity\Team;
use App\Entity\Tower;
use App\Services\API\LOL\DataDragon\Queue;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\TeamDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
use App\Services\API\LOL\LeagueOfLegends\LeagueApi;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{

    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/statistique/{tier}/{division}', name: 'app_stat_index',
        requirements: ['tier' => '%reqTier%', 'division' => '%reqDivision%'])]
    public function index(
        string $tier,
        string $division,
        LeagueApi $leagueApi,
        SummonerApi $summonerApi,
        MatchApi $matchApi
    ) {
        $leagues = $leagueApi->leagueByQueueByTierByDivision(
            Queue::RANKED_SOLO,
            $tier,
            $division)
        ;

        foreach($leagues as $leagueApi) {

            $invocateur =
                $this->doctrine->getRepository(Invocateur::class)
                    ->findOrderByCreatedAt($leagueApi->getSummonerId());

            // Verif invocateur existe
            if($invocateur === null) {
                // Creation de l'invocateur
                $sumApi = $summonerApi->summonerBySummonerId($leagueApi->getSummonerId());
                if($sumApi === null) {
                    continue;
                }
                $invocateur = $this->createInvocateur($sumApi);

                $this->doctrine->getManager()->persist($invocateur);
            }

            // On verif si l'invocateur à eu une historique League enregistré il y a plus de un jour
            if(count($invocateur->getHistoriqueLeagues()) > 0 &&
                $invocateur->getHistoriqueLeagues()[0]->getCreateAt()->diff(new \DateTimeImmutable())->days <= 1
            ) {
                continue;
            }

            // On créé l'historique League
            $league = $this->createHistoriqueLeague($leagueApi, $invocateur);


            // On récupère les ID Matchs de l'invocateur
            $idMatchs = $matchApi->getMatchByPuuid($invocateur->getPuuid(),0,20,'ranked');
            if($idMatchs === null) {
                continue;
            }

            foreach($idMatchs as $idmatch) {
                // On verifie si le match existe déjà
                $rencontreRepo = $this->doctrine->getRepository(Rencontre::class)
                    ->findOneBy(['gameId' => (int)substr(strstr($idmatch, '_'),1, null)]);

                // On le récupère les infos du match et on l'enregistre
                if($rencontreRepo === null) {
                    $match = $matchApi->getMatchById($idmatch);
                    if($match === null) {
                        continue;
                    }

                    $map = $this->doctrine->getRepository(Map::class)->findOneBy(['mapIdLol' => $match->getInfo()->getMapId()]);

                    $rencontre = $this->createRencontre($match, $map,$invocateur);
                    $this->doctrine->getManager()->persist($rencontre);
                }
            }
            $this->doctrine->getManager()->persist($league);
        }
        $this->doctrine->getManager()->flush();

        return new JsonResponse("Traitement fini");
    }

    private function createInvocateur(
        SummonerDTO $sumApi
    ): Invocateur
    {
        return (new Invocateur())
                ->setName($sumApi->getName())
                ->setSummonerLevel($sumApi->getSummonerLevel())
                ->setPuuid($sumApi->getPuuid())
                ->setIdLol($sumApi->getId())
                ->setAccoundId($sumApi->getAccountId())
                ->setProfileIconId($sumApi->getProfileIconId())
            ;
    }

    private function createHistoriqueLeague(
        LeagueEntryDTO $leagueApi,
        Invocateur $invocateur
    ): HistoriqueLeague
    {
        return (new HistoriqueLeague())
                ->setLeagueId($leagueApi->getLeagueId())
                ->setLeaguePoint($leagueApi->getLeaguePoints())
                ->setRank($leagueApi->getRank())
                ->setTier($leagueApi->getTier())
                ->setWins($leagueApi->getWins() ?: 0)
                ->setLosses($leagueApi->getLosses() ?: 0)
                ->setInvocateur($invocateur)
            ;
    }

    private function createRencontre(
        MatchDto $match,
        Map $maps,
        Invocateur $invocateur
    )
    {
        $rencontre = (new Rencontre())
            ->setGameId($match->getInfo()->getGameId())
            ->setGameDuration($match->getInfo()->getGameDuration())
            ->setGameCreation($match->getInfo()->getGameCreation())
            ->setMap($maps)
            ->addInvocateur($invocateur)
        ;

        foreach ($match->getInfo()->getTeams() as $teamApi) {

            $tower = $this->createTower($teamApi);

            $baron = $this->createBaron($teamApi);

            $inhibitor = $this->createInhibitor($teamApi);

            $riftHerald = $this->createRiftHerald($teamApi);

            $dragon = $this->createDragon($teamApi);

            $team = $this->createTeam($teamApi, $tower, $dragon, $riftHerald, $baron, $inhibitor);

            $this->doctrine->getManager()->persist($team);

            $rencontre->addTeam($team);
        }
        return $rencontre;
    }

    private function createTower(
        TeamDto $teamApi
    )
    {
        return (new Tower())
            ->setFirst($teamApi->getObjectives()->getTower()->isFirst())
            ->setKills($teamApi->getObjectives()->getTower()->getKills())
            ;
    }

    private function createBaron(
        TeamDto $teamApi
    )
    {
        return (new Baron())
            ->setFirst($teamApi->getObjectives()->getTower()->isFirst())
            ->setKills($teamApi->getObjectives()->getTower()->getKills())
            ;
    }

    private function createInhibitor(
        TeamDto $teamApi
    )
    {
        return (new Inhibitor())
            ->setFirst($teamApi->getObjectives()->getTower()->isFirst())
            ->setKills($teamApi->getObjectives()->getTower()->getKills())
            ;
    }

    private function createRiftHerald(
        TeamDto $teamApi
    )
    {
        return (new RiftHerald())
            ->setFirst($teamApi->getObjectives()->getTower()->isFirst())
            ->setKills($teamApi->getObjectives()->getTower()->getKills())
            ;
    }

    private function createDragon
    (TeamDto $teamApi
    )
    {
        return (new Dragon())
            ->setFirst($teamApi->getObjectives()->getTower()->isFirst())
            ->setKills($teamApi->getObjectives()->getTower()->getKills())
        ;
    }

    private function createTeam(
        TeamDto $teamApi,
        Tower $tower,
        Dragon $dragon,
        RiftHerald $riftHerald,
        Baron $baron,
        Inhibitor $inhibitor
    ) {
        return (new Team())
            ->setTeamIdLol($teamApi->getTeamId())
            ->setBan1ChampionId($teamApi->getBans()[0]->getChampionId())
            ->setBan2ChampionId($teamApi->getBans()[1]->getChampionId())
            ->setBan3ChampionId($teamApi->getBans()[2]->getChampionId())
            ->setBan4ChampionId($teamApi->getBans()[3]->getChampionId())
            ->setBan5ChampionId($teamApi->getBans()[4]->getChampionId())
            ->setTower($tower)
            ->setDragon($dragon)
            ->setRiftHerald($riftHerald)
            ->setBaron($baron)
            ->setInhibitor($inhibitor)
            ->setWin($teamApi->isWin())
        ;
    }
}