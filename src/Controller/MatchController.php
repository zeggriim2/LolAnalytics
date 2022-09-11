<?php

namespace App\Controller;

use App\Entity\Baron;
use App\Entity\Dragon;
use App\Entity\Inhibitor;
use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Entity\RiftHerald;
use App\Entity\Team;
use App\Entity\Tower;
use App\Form\InvocateurType;
use App\Repository\InvocateurRepository;
use App\Repository\RencontreRepository;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use App\Traits\InvocateurTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MatchController extends AbstractController
{
    use InvocateurTrait;

    public function __construct(
        private MatchApi $matchApi,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/match', name: 'app_match')]
    public function index(
        Request                 $request,
        InvocateurRepository    $invocateurRepository,
        RencontreRepository     $rencontreRepository,
        SummonerApi             $summonerApi
    ): Response
    {
        $form = $this->createForm(InvocateurType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $name = $form->get('name')->getData();
            $invocateur = $invocateurRepository->isInvocateurExiste($name);
            if($invocateur === null){
                // On crée l'invocateur s'il n'existe pas
                $summonerApiDto = $summonerApi->summonerBySummonerName($name);
                if($summonerApiDto === null){
                    // On Add Flash l'invocateur erreur
                }
                $invocateur = $this->create($summonerApiDto);
            }
            $idMatchs = $this->matchApi->getMatchByPuuid($invocateur->getPuuid());

            if($idMatchs === null){
                // On Add Flash match erreur
            }

            foreach ($idMatchs as $idMatch){
                $rencontre = $rencontreRepository->isGameId($idMatch);
                if ($rencontre === null){
                    $this->createRencontre($idMatch,$invocateur);
                }
            }

            return $this->redirectToRoute('app_invocateur',['name' => $invocateur->getName()]);
        }

        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{name}', name: 'app_invocateur')]
    public function invocateur(Invocateur $invocateur)
    {
        dd($invocateur);
    }

    /**
     * @param string $gameId
     * @param Invocateur $invocateur
     * @return Rencontre
     */
    private function createRencontre(string $gameId,Invocateur $invocateur)
    {
        $matchDto = $this->matchApi->getMatchById($gameId);
        $map = $this->em->getRepository(Map::class)->findOneBy(['mapIdLol' => $matchDto->getInfo()->getMapId()]);

        $teams = $matchDto->getInfo()->getTeams();

        $rencontre = (new Rencontre())
            ->setGameId($matchDto->getInfo()->getGameId())
            ->setMap($map ?? null)
            ->setGameCreation($matchDto->getInfo()->getGameCreation())
            ->setGameDuration($matchDto->getInfo()->getGameDuration())
            ->addInvocateur($invocateur)
        ;

        foreach ($teams as $team){

            $tower = (new Tower())
                ->setKills($team['objectives']['tower']['kills'])
                ->setFirst($team['objectives']['tower']['first'])
            ;

            $inhibitor = (new Inhibitor())
                ->setKills($team['objectives']['inhibitor']['kills'])
                ->setFirst($team['objectives']['inhibitor']['first'])
            ;

            $dragon = (new Dragon())
                ->setKills($team['objectives']['dragon']['kills'])
                ->setFirst($team['objectives']['dragon']['first'])
            ;

            $baron = (new Baron())
                ->setKills($team['objectives']['baron']['kills'])
                ->setFirst($team['objectives']['baron']['first'])
            ;

            $riftHerald = (new RiftHerald())
                ->setKills($team['objectives']['riftHerald']['kills'])
                ->setFirst($team['objectives']['riftHerald']['first'])
            ;

            $team = (new Team())
                ->setTeamIdLol($team['teamId'])
                ->setBan1ChampionId(count($team['bans']) > 0 ? $team['bans'][0]->getChampionId() : null)
                ->setBan2ChampionId(count($team['bans']) > 0 ? $team['bans'][1]->getChampionId() : null)
                ->setBan3ChampionId(count($team['bans']) > 0 ? $team['bans'][2]->getChampionId() : null)
                ->setBan4ChampionId(count($team['bans']) > 0 ? $team['bans'][3]->getChampionId() : null)
                ->setBan5ChampionId(count($team['bans']) > 0 ? $team['bans'][4]->getChampionId() : null)
                ->setWin($team['win'])
                ->setTower($tower)
                ->setInhibitor($inhibitor)
                ->setBaron($baron)
                ->setRiftHerald($riftHerald)
                ->setDragon($dragon)
                ->setRencontre($rencontre)
            ;
            $this->em->persist($team);
        }

        $this->em->persist($rencontre);
        $this->em->flush();

        return $rencontre;
    }
}
