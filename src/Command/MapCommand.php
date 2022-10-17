<?php

namespace App\Command;

use App\Entity\Map;
use App\Services\API\LOL\DataDragon\GeneralApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:maps',
    description: 'Ajout les Maps en BDD',
)]
class MapCommand extends Command
{
    public function __construct(
        private GeneralApi $generalApi,
        private EntityManagerInterface $doctrine,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $maps = $this->generalApi->getMaps();
        $io->progressStart();

        $countMaps = 0;
        if (null !== $maps) {
            foreach ($maps as $map) {
                $io->progressAdvance();
                $mapRepo = $this->doctrine->getRepository(Map::class)->findOneBy(['mapIdLol' => $map['mapId']]);
                if (null === $mapRepo) {
                    $mapEntity = (new Map())
                        ->setMapIdLol($map['mapId'])
                        ->setName($map['mapName'])
                        ->setNotes($map['notes'])
                    ;
                    $this->doctrine->persist($mapEntity);
                    ++$countMaps;
                }
            }
        }
        $this->doctrine->flush();

        if (0 === $countMaps) {
            $phrase = "Aucun élément de map n'a été ajouté.";
        } else {
            $phrase = "$countMaps map ont été ajouté en bdd.";
        }

        $io->success($phrase);

        $io->progressFinish();

        return Command::SUCCESS;
    }
}
