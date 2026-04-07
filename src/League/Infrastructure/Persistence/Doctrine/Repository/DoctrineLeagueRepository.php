<?php

declare(strict_types=1);

namespace App\League\Infrastructure\Persistence\Doctrine\Repository;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\League\Infrastructure\Persistence\Doctrine\Entity\LeagueEntity;
use App\SharedContext\Domain\ValueObjet\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class DoctrineLeagueRepository implements LeagueRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findByTierQueuePlatform(LeagueTier $tier, Queue $queue, Platform $platform): ?League
    {
        $entity = $this->entityManager
            ->getRepository(LeagueEntity::class)
            ->findOneBy([
                'tier' => $tier,
                'queue' => $queue->value,
                'platform' => $platform->value,
            ]);

        return $entity?->toDomain();
    }

    public function save(League $league): void
    {
        $existing = $this->entityManager
            ->getRepository(LeagueEntity::class)
            ->findOneBy([
                'tier' => $league->tier(),
                'queue' => $league->queue()->value,
                'platform' => $league->platform()->value,
            ]);

        if (null === $existing) {
            $entity = LeagueEntity::fromDomain($league);
            $this->entityManager->persist($entity);
        } else {
            $existing->updateFromDomain($league);
        }

        $this->entityManager->flush();
    }
}
