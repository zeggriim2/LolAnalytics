<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\Queue;
use App\GameData\Domain\Repository\QueueRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\QueueEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineQueueRepository implements QueueRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Queue $queue): void
    {
        $existing = $this->em->find(QueueEntity::class, $queue->queueId());

        if (null !== $existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $entity = QueueEntity::fromDomain($queue);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByQueueId(int $queueId): ?Queue
    {
        $entity = $this->em->find(QueueEntity::class, $queueId);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(QueueEntity::class)->findAll();

        return array_map(fn (QueueEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(QueueEntity::class, 'q')
            ->getQuery()
            ->execute();
    }
}
