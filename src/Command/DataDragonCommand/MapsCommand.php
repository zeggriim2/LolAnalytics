<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Map;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;

#[AsCommand(
    name: 'app:maps',
    description: 'Ajoute les maps en Bdd.',
)]
final class MapsCommand extends Command
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

        $maps = $this->dataDragonApi->getMaps(TypeReturn::RETURN_OBJET);
        $io->progressStart(count($maps));
        $countMapAdd = 0;
        foreach ($maps as $map) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(Map::class)
                ->findOneBy(['idLol' => $map->getMapId()])) {
                $countMapAdd++;
                $mapEntity = (new Map())
                    ->setIdLol($map->getMapId())
                    ->setName($map->getNotes())
                    ->setNotes($map->getNotes())
                ;

                $this->managerRegistry->getManager()->persist($mapEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Maps on été ajoutées ($countMapAdd)");

        return Command::SUCCESS;
    }
}
