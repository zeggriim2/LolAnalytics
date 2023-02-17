<?php

namespace App\Command\DataDragonCommand;

use App\Entity\Queue;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zeggriim\RiotApiDatadragon\DataDragonApi;
use Zeggriim\RiotApiDatadragon\Enum\TypeReturn;

#[AsCommand(
    name: 'app:queues',
    description: 'Ajoute les queues en Bdd.',
)]
final class QueuesCommand extends Command
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

        $queues = $this->dataDragonApi->getQueues(TypeReturn::RETURN_OBJET);
        $io->progressStart(count($queues));
        $countQueueAdd = 0;
        foreach ($queues as $queue) {
            $io->progressAdvance();
            if(!$this->managerRegistry->getRepository(Queue::class)
                ->findOneBy(['idLol' => $queue->getQueueId()])) {
                $countQueueAdd++;
                $queueEntity = (new Queue())
                    ->setIdLol($queue->getQueueId())
                    ->setMap($queue->getMap())
                    ->setDescription($queue->getDescription())
                    ->setNotes($queue->getNotes())
                ;

                $this->managerRegistry->getManager()->persist($queueEntity);
            }
        }
        $this->managerRegistry->getManager()->flush();

        $io->progressFinish();
        $io->success("Toute les Queues on été ajoutées ($countQueueAdd)");

        return Command::SUCCESS;
    }
}
