<?php

namespace App\Command\DataDragonCommand;

use App\Entity\GameMode;
use App\Entity\GameType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;

#[AsCommand(
    name: 'app:gametypes',
    description: 'Ajoute les GameTypes en Bdd.',
)]
final class GameTypesCommand extends Command
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

        $gameTypes = $this->dataDragonApi->getGameTypes(TypeReturn::RETURN_OBJET);
        $io->progressStart(count($gameTypes));
        $countGameTypeAdd = 0;
        foreach ($gameTypes as $gameType) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(GameType::class)
                ->findOneBy(['gameType' => $gameType->getGametype()])) {
                $countGameTypeAdd++;
                $gameTypeEntity = (new GameType())
                    ->setGameType($gameType->getGametype())
                    ->setDescription($gameType->getDescription())
                ;
                $this->managerRegistry->getManager()->persist($gameTypeEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les GameTypes on été ajoutées ($countGameTypeAdd)");

        return Command::SUCCESS;
    }
}
