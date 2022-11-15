<?php

namespace App\Command;

use App\Command\Traits\InvocateurTrait;
use App\Command\Traits\RencontreTrait;
use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
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
    use InvocateurTrait;
    use RencontreTrait;

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

        $critere = $puuid ? ['puuid' => $puuid] : [];

        // On va voir si un invocateur existe déja dans la base
        if(
            (null === $invocateur = $this->doctrine->getRepository(Invocateur::class)->findOneBy($critere)) &&
                $puuid !== null
        ) {
            // On va récupérer les informations de l'invocateur par API comme on ne l'a pas en BDD
            if(null !== $summonerApi = $this->summonerApi->summonerByPuuid($puuid)) {
                $invocateur = $this->invocateur($summonerApi);
            }
        }

        // On check si un invocateur à été trouvé dans la BDD ou récupérer par API
        if($invocateur === null) {
            $io->error('Invocateur introuvable');
            return Command::INVALID;
        }

        // On récupère par API les ID des games réalisé par l'invocateur
        if(null === $matchsIdApi = $this->matchApi->getMatchByPuuid($invocateur->getPuuid())) {
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
                $map = $this->doctrine->getRepository(Map::class)->findOneBy(['mapIdLol' => $match->getInfo()->getMapId()]);
                $rencontre = $this->rencontre($match, $map);
                $participants = $match->getMetadata()->getParticipants();

                foreach($participants as $participant) {
                    $invocateurRepo = null;
                    if (
                        $invocateur->getPuuid() !== $participant &&
                        (null === $invocateurRepo = $this->doctrine->getRepository(Invocateur::class)->findOneBy(['puuid'=> $participant]))
                    ){
                        if(null !== $summonerApi = $this->summonerApi->summonerByPuuid($participant)) {
                            $invocateurRepo = $this->invocateur($summonerApi);
                        }
                    }
                    if($invocateurRepo) {
                        $rencontre->addInvocateur($invocateurRepo);
                    }
                }

                $this->doctrine->persist($rencontre);
            }
        }
        $io->progressFinish();
        $this->doctrine->flush();

        return Command::SUCCESS;
    }
}
