<?php

namespace App\Command\DataDragonCommand;

use App\Entity\DivisionLeague;
use App\Entity\QueueLeague;
use App\Entity\TierLeague;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiLeague\Enum\League\Division;
use Zeggriim\RiotApiLeague\Enum\League\Queue;
use Zeggriim\RiotApiLeague\Enum\League\Tier;

#[AsCommand(
    name: 'app:init-league',
    description: 'Initialise les données pour les Leagues',
)]
final class InitleagueCommand extends Command
{

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $reflectionTier = new \ReflectionClass(Tier::class);
        $reflectionQueue = new \ReflectionClass(Queue::class);
        $reflectionDvision = new \ReflectionClass(Division::class);
        $tierConstants = $reflectionTier->getConstants();
        $queueConstants = $reflectionQueue->getConstants();
        $divisionConstants = $reflectionDvision->getConstants();
        $io->progressStart(count($tierConstants));

        foreach ($tierConstants as $name => $value) {
            $io->progressAdvance();
            if ($reflectionTier->getConstant($name) === $value) {
                $tier = (new TierLeague())
                    ->setCode($value)
                    ;
                $this->managerRegistry->getManager()->persist($tier);
            }
        }
        $io->progressFinish();
        $io->success("Toute les Tiers de la league ont été ajouté");

        $io->progressStart(count($queueConstants));
        foreach($queueConstants as $name => $value) {
            $io->progressAdvance();
            if ($reflectionQueue->getConstant($name) === $value) {
                $queue = (new QueueLeague())
                    ->setCode($value)
                ;
                $this->managerRegistry->getManager()->persist($queue);
            }
        }

        $io->progressFinish();
        $io->success("Toute les Queues de la league ont été ajouté");

        $io->progressStart(count($divisionConstants));
        foreach($divisionConstants as $name => $value) {
            $io->progressAdvance();
            if ($reflectionDvision->getConstant($name) === $value) {
                $division = (new DivisionLeague())
                    ->setCode($value)
                ;
                $this->managerRegistry->getManager()->persist($division);
            }
        }

        $io->progressFinish();
        $io->success("Toute les Divisions de la league ont été ajouté");

        $this->managerRegistry->getManager()->flush();


        return Command::SUCCESS;
    }
}
