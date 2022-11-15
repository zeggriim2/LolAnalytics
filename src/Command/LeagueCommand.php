<?php

namespace App\Command;

use App\Command\Traits\InvocateurTrait;
use App\Command\Traits\LeagueTrait;
use App\Entity\Invocateur;
use App\Entity\League;
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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:league',
    description: 'Add a short description for your command',
)]
class LeagueCommand extends Command
{
    use InvocateurTrait;
    use LeagueTrait;

    private int $countLeague = 0;
    private int $countInvocateur = 0;

    public function __construct(
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
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limite')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $division = $input->getArgument('division');
        $tier = $input->getArgument('tier');
        $queue = $input->getArgument('queue');
        $limit = $input->getArgument('limit');

        if(!in_array($division, Division::ALL_DIVISION) || !in_array($tier, Tier::ALL_TIERS)) {
            $io->error("Division ou Tier incorrect");
            return Command::INVALID;
        }

        $leagues = $this->leagueApi->leagueByQueueByTierByDivision($queue, $tier, $division);

        if ($leagues === null) {
            $io->error("Aucune league trouvé ($division, $tier)");
            return Command::INVALID;
        }

        if($limit){
            $leagues = array_slice($leagues,0, $limit);
        }

        $io->progressStart(count($leagues));

        foreach ($leagues as $league) {
            $io->progressAdvance();
            if(
                $this->doctrine
                    ->getRepository(League::class)->findOneBy(['leagueId' => $league->getLeagueId()]) === null
            ) {
                $invocateur =
                    $this->doctrine->getRepository(Invocateur::class)->findOneBy(['idLol'=> $league->getSummonerId()]);
                if($invocateur === null) {
                    $summonerApi = $this->summonerApi->summonerBySummonerId($league->getSummonerId());
                    if($summonerApi){
                        $this->countInvocateur++;
                        $invocateur = $this->invocateur($summonerApi, false);
                    }
                }
                if($invocateur) {
                    $this->countLeague++;
                    $this->league($invocateur, $league);
                }
            }
        }

        $io->progressFinish();


        $phraseInvocateur = $this->phraseOutput($this->countInvocateur, 'Invocateur');
        $io->success($phraseInvocateur);

        $phraseLeague =$this->phraseOutput($this->countLeague, 'League');
        $io->success($phraseLeague);

        $this->doctrine->flush();

        return Command::SUCCESS;
    }

    private function phraseOutput(int $count,string $libelle): string
    {
        if (0 === $count) {
            $phrase = "Aucun élément de $libelle n'a été ajouté.";
        } else {
            $phrase = "$count $libelle ont été ajouté en bdd.";
        }

        return $phrase;
    }
}
