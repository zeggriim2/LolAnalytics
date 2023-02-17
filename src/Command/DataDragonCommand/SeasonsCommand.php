<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Season;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;

#[AsCommand(
    name: 'app:seasons',
    description: 'Ajoute les season en Bdd.',
)]
final class SeasonsCommand extends Command
{
    private DataDragonApi $dataDragonApi;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        parent::__construct();
        $this->dataDragonApi = new DataDragonApi();
    }
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $seasons = $this->dataDragonApi->getSeasons(TypeReturn::RETURN_OBJET);
        $io->progressStart(count($seasons));
        $countSeasonAdd = 0;
        foreach ($seasons as $season) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(Season::class)
                ->findOneBy(['idLol' => $season->getId()])) {
                $countSeasonAdd++;
                $seasonEntity = (new Season())
                    ->setIdLol($season->getId())
                    ->setSeason($season->getSeason())
                ;

                $this->managerRegistry->getManager()->persist($seasonEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Seasons on été ajoutées ($countSeasonAdd)");

        return Command::SUCCESS;
    }
}
