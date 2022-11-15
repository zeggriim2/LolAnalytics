<?php

namespace App\Command;

use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use App\Services\API\LOL\LeagueOfLegends\SummonerApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:matchs',
    description: 'Ajout les versions en BDD',
)]
class MatchCommand extends Command
{
    public function __construct(
        private MatchApi $matchApi,
        private SummonerApi $summonerApi,
        private EntityManagerInterface $doctrine,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('puuid', InputArgument::OPTIONAL, "Puuid de l'invocateur")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $puuid = $input->getArgument('puuid');

        $critere = [];
        if($puuid) {
            $critere['puuid'] = $puuid;
        }

        $invocateur = $this->doctrine->getRepository(Invocateur::class)->findOneBy($critere);


        if($puuid !== null && $invocateur === null) {
            $invocateur = $this->invocateur($puuid);
        }

        if(!$invocateur){
            $io->error('Invocateur introuvable');
            return Command::INVALID;
        }

        $matchsIdApi = $this->matchApi->getMatchByPuuid($invocateur->getPuuid());

        if($matchsIdApi=== null) {
            $io->error('Match non trouvé');
            return Command::INVALID;
        }

        $io->progressStart(count($matchsIdApi));

        foreach ($matchsIdApi as $matchId) {
            $io->progressAdvance();
            $match = $this->matchApi->getMatchById($matchId);
            if(
                $match !== null &&
                $this->doctrine->getRepository(Rencontre::class)
                    ->findOneBy(['gameId' => $match->getInfo()->getGameId()]) === null
            ){
                $this->rencontre($match, $invocateur);
            }
        }
        $io->progressFinish();
        $this->doctrine->flush();

//        $io->success($phrase);

        return Command::SUCCESS;
    }

    private function rencontre(MatchDto $matchDto,Invocateur $invocateur): void
    {

        $map = $this->doctrine->getRepository(Map::class)->findOneBy(['mapIdLol' => $matchDto->getInfo()->getMapId()]);

        $rencontre = (new Rencontre())
            ->setGameId($matchDto->getInfo()->getGameId())
            ->setGameDuration($matchDto->getInfo()->getGameDuration())
            ->setGameCreation(
                (new \DateTimeImmutable())->setTimestamp((int)floor($matchDto->getInfo()->getGameCreation() / 1000))
            )
            ->setMap($map)
            ->setGameMode($matchDto->getInfo()->getGameMode())
            ->setGameVersion($matchDto->getInfo()->getGameVersion())
            ->addInvocateur($invocateur)
        ;

        $participants = $matchDto->getMetadata()->getParticipants();
        foreach($participants as $participant) {
            $invocateurRepo = null;
            if ($invocateur->getPuuid() !== $participant){
                $invocateurRepo = $this->doctrine->getRepository(Invocateur::class)->findOneBy(['puuid'=> $participant]);
                if($invocateurRepo === null) {
                    $invocateurRepo = $this->invocateur($participant);
                }
                if($invocateurRepo) {
                    $rencontre->addInvocateur($invocateurRepo);
                }
            }
        }

        $this->doctrine->persist($rencontre);

    }

    private function invocateur(string $puuid): ?Invocateur
    {
        $summonerApi = $this->summonerApi->summonerByPuuid($puuid);
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
            $this->doctrine->flush();
            return $invocateur;
        }
        return null;
    }
}
