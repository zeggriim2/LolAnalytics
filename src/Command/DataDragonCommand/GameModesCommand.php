<?php

namespace App\Command\DataDragonCommand;

use App\Entity\GameMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;

#[AsCommand(
    name: 'app:gamemodes',
    description: 'Ajoute les GameModes en Bdd.',
)]
final class GameModesCommand extends Command
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

        $gameModes = $this->dataDragonApi->getGameModes(TypeReturn::RETURN_OBJET);
        $io->progressStart(count($gameModes));
        $countGameModeAdd = 0;
        foreach ($gameModes as $gameMode) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(GameMode::class)
                ->findOneBy(['gameMode' => $gameMode->getGameMode()])) {
                $countGameModeAdd++;
                $gameModeEntity = (new GameMode())
                    ->setGameMode($gameMode->getGameMode())
                    ->setDescription($gameMode->getDescription())
                ;

                $this->managerRegistry->getManager()->persist($gameModeEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les GameModes on été ajoutées ($countGameModeAdd)");

        return Command::SUCCESS;
    }
}
