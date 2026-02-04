<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\Queue;

interface QueueRepositoryInterface
{
    public function save(Queue $queue): void;

    public function findByQueueId(int $queueId): ?Queue;

    /**
     * @return Queue[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
