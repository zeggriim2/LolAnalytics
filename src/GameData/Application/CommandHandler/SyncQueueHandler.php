<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncQueueCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\Queue;
use App\GameData\Domain\Repository\QueueRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncQueueHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly QueueRepositoryInterface $queueRepository,
    ) {
    }

    public function __invoke(SyncQueueCommand $command): void
    {
        $dtos = $this->dataProvider->fetchQueues();

        foreach ($dtos as $dto) {
            $queue = new Queue(
                $dto->queueId,
                $dto->map,
                $dto->description,
                $dto->notes,
            );
            $this->queueRepository->save($queue);
        }
    }
}
