<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\Map;
use App\GameData\Domain\Repository\MapRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\MapEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineMapRepository implements MapRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Map $map): void
    {
        $existing = $this->em->find(MapEntity::class, $map->mapId());

        if (null !== $existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $entity = MapEntity::fromDomain($map);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByMapId(int $mapId): ?Map
    {
        $entity = $this->em->find(MapEntity::class, $mapId);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(MapEntity::class)->findAll();

        return array_map(fn (MapEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(MapEntity::class, 'm')
            ->getQuery()
            ->execute();
    }
}
